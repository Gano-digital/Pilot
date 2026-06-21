import type { Request, Response } from 'express';
import { sendEmail } from '../../email.js';
import { sanitizeHeaderValue, sanitizeReplyTo } from '../../lib/email-safety.js';
import {
  DIAGNOSTICO_STEPS,
  computeDiagnostico,
  SEVERITY_LABELS,
  type DiagnosticoAnswers,
  type Severity,
} from '../../../data/diagnostico.js';

const RECIPIENT = process.env.CONTACT_FORM_RECIPIENT_EMAIL ?? 'pymes@gano.digital';

function sanitize(value: unknown, maxLen = 254): string {
  if (typeof value !== 'string') return '';
  return value.trim().slice(0, maxLen);
}

/** Normaliza el payload de respuestas: solo step.id y option.value válidos. */
function normalizeAnswers(raw: unknown): DiagnosticoAnswers {
  const out: DiagnosticoAnswers = {};
  if (!raw || typeof raw !== 'object') return out;
  const obj = raw as Record<string, unknown>;
  for (const step of DIAGNOSTICO_STEPS) {
    const val = obj[step.id];
    const arr = Array.isArray(val) ? val : typeof val === 'string' ? [val] : [];
    const valid = step.options.map((o) => o.value);
    const filtered = arr
      .filter((v): v is string => typeof v === 'string')
      .filter((v) => valid.includes(v));
    if (filtered.length) out[step.id] = filtered;
  }
  return out;
}

/** Etiqueta legible de la opción seleccionada (para el email). */
function optionLabel(stepId: string, value: string): string {
  const step = DIAGNOSTICO_STEPS.find((s) => s.id === stepId);
  return step?.options.find((o) => o.value === value)?.label ?? value;
}

const SEVERITY_COLOR: Record<Severity, string> = {
  critico: '#E5484D',
  atencion: '#D97E3A',
  oportunidad: '#9BA89B',
};

export default async function handler(req: Request, res: Response) {
  const name = sanitize(req.body?.name, 100);
  const email = sanitize(req.body?.email, 254);
  const company = sanitize(req.body?.company, 120);
  const phone = sanitize(req.body?.phone, 40);
  const answers = normalizeAnswers(req.body?.answers);

  if (name.length < 2) {
    return res.status(400).json({ error: 'Nombre inválido.' });
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) {
    return res.status(400).json({ error: 'Correo inválido.' });
  }
  if (Object.keys(answers).length < 3) {
    return res.status(400).json({ error: 'Completa el diagnóstico antes de enviarlo.' });
  }

  // El servidor recalcula — nunca confía en el cliente.
  const result = computeDiagnostico(answers);

  // ── Resumen de respuestas (para texto y html) ──
  const answeredAreas = DIAGNOSTICO_STEPS.filter((s) => answers[s.id]?.length).map((s) => ({
    area: s.area,
    question: s.question,
    picks: answers[s.id].map((v) => optionLabel(s.id, v)),
  }));

  // ── Texto plano ──
  const textBody = [
    `Nuevo Diagnóstico Operativo 360° desde gano.digital`,
    ``,
    `Nombre:   ${name}`,
    `Empresa:  ${company || 'No especificada'}`,
    `Correo:   ${email}`,
    `Teléfono: ${phone || 'No especificado'}`,
    ``,
    `Áreas con necesidades detectadas: ${result.areasWithNeeds}`,
    `Hallazgos críticos (seguridad):   ${result.hasCritical ? 'SÍ' : 'No'}`,
    `Amerita asesoría profesional:     ${result.needsAdvisory ? 'SÍ' : 'No'}`,
    ``,
    `── RESPUESTAS ──`,
    ...answeredAreas.map((a) => `• ${a.area}: ${a.picks.join(' · ')}`),
    ``,
    `── RECOMENDACIONES SUGERIDAS ──`,
    ...result.recommendations.map(
      (r) => `[${SEVERITY_LABELS[r.severity]}] ${r.title} → ${r.reason}`,
    ),
    ``,
    `---`,
    `Responde directamente a este correo para contactar al lead.`,
  ].join('\n');

  // ── HTML ──
  const recsHtml = result.recommendations
    .map(
      (r) => `
      <tr>
        <td style="padding:14px 0;border-bottom:1px solid #2A2A2A;">
          <span style="display:inline-block;background:${SEVERITY_COLOR[r.severity]};color:#0A0A0A;font-size:10px;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;padding:3px 8px;border-radius:3px;">${escapeHtml(SEVERITY_LABELS[r.severity])}</span>
          <p style="margin:8px 0 4px;color:#F5F5F5;font-size:15px;font-weight:700;">${escapeHtml(r.title)}</p>
          <p style="margin:0;color:#999;font-size:13px;line-height:1.6;">${escapeHtml(r.reason)}</p>
        </td>
      </tr>`,
    )
    .join('');

  const answersHtml = answeredAreas
    .map(
      (a) => `
      <tr>
        <td style="padding:10px 0;border-bottom:1px solid #2A2A2A;">
          <p style="margin:0 0 2px;color:#888;font-size:11px;text-transform:uppercase;letter-spacing:0.12em;">${escapeHtml(a.area)}</p>
          <p style="margin:0;color:#F5F5F5;font-size:14px;">${escapeHtml(a.picks.join(' · '))}</p>
        </td>
      </tr>`,
    )
    .join('');

  const htmlBody = `
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#0A0A0A;font-family:system-ui,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#0A0A0A;padding:40px 20px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#1A1A1A;border-radius:8px;overflow:hidden;max-width:600px;width:100%;">
        <tr>
          <td style="background:#D97E3A;padding:24px 32px;">
            <p style="margin:0;color:#0A0A0A;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-weight:600;">Gano Digital</p>
            <h1 style="margin:8px 0 0;color:#0A0A0A;font-size:22px;font-weight:700;letter-spacing:-0.02em;">Nuevo Diagnóstico Operativo 360°</h1>
          </td>
        </tr>
        <tr>
          <td style="padding:32px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr><td style="padding-bottom:16px;border-bottom:1px solid #2A2A2A;">
                <p style="margin:0 0 4px;color:#888;font-size:11px;text-transform:uppercase;letter-spacing:0.15em;">Lead</p>
                <p style="margin:0;color:#F5F5F5;font-size:16px;font-weight:600;">${escapeHtml(name)}${company ? ` — ${escapeHtml(company)}` : ''}</p>
                <p style="margin:6px 0 0;"><a href="mailto:${escapeHtml(email)}" style="color:#D97E3A;font-size:14px;text-decoration:none;">${escapeHtml(email)}</a>${phone ? ` <span style="color:#777;">· ${escapeHtml(phone)}</span>` : ''}</p>
              </td></tr>
              <tr><td style="padding:16px 0;border-bottom:1px solid #2A2A2A;">
                <p style="margin:0;color:#F5F5F5;font-size:13px;">
                  <strong style="color:#D97E3A;">${result.areasWithNeeds}</strong> áreas con necesidades ·
                  Críticos: <strong style="color:${result.hasCritical ? '#E5484D' : '#9BA89B'};">${result.hasCritical ? 'Sí' : 'No'}</strong> ·
                  Asesoría: <strong style="color:#D97E3A;">${result.needsAdvisory ? 'Sí' : 'No'}</strong>
                </p>
              </td></tr>
            </table>

            <p style="margin:24px 0 8px;color:#888;font-size:11px;text-transform:uppercase;letter-spacing:0.15em;">Respuestas</p>
            <table width="100%" cellpadding="0" cellspacing="0">${answersHtml}</table>

            <p style="margin:28px 0 8px;color:#888;font-size:11px;text-transform:uppercase;letter-spacing:0.15em;">Recomendaciones sugeridas</p>
            <table width="100%" cellpadding="0" cellspacing="0">${recsHtml}</table>
          </td>
        </tr>
        <tr>
          <td style="padding:0 32px 32px;">
            <a href="mailto:${escapeHtml(email)}" style="display:inline-block;background:#D97E3A;color:#0A0A0A;font-size:13px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;text-decoration:none;padding:14px 28px;border-radius:4px;">
              Responder a ${escapeHtml(name)}
            </a>
          </td>
        </tr>
        <tr>
          <td style="padding:20px 32px;border-top:1px solid #2A2A2A;">
            <p style="margin:0;color:#555;font-size:12px;">Generado por el Diagnóstico Operativo de <a href="https://gano.digital" style="color:#D97E3A;text-decoration:none;">gano.digital</a></p>
          </td>
        </tr>
      </table>
    </td></tr>
  </table>
</body>
</html>`;

  try {
    // Subject y Reply-To van a CABECERAS de correo: se sanean contra CRLF
    // injection antes de salir de nuestro código (defensa en profundidad).
    const safeSubject = sanitizeHeaderValue(
      `[Gano Digital] Diagnóstico 360°: ${name}${company ? ` (${company})` : ''} — ${result.areasWithNeeds} áreas`,
    );
    const safeReplyTo = sanitizeReplyTo(email);

    await sendEmail({
      to: RECIPIENT,
      subject: safeSubject,
      text: textBody,
      html: htmlBody,
      ...(safeReplyTo ? { replyTo: safeReplyTo } : {}),
    });

    // Devolvemos el resultado calculado en servidor para que el cliente muestre el informe.
    return res.status(200).json({ ok: true, result });
  } catch (err) {
    console.error('diagnostico.send-failed', {
      error: err instanceof Error ? err.message : String(err),
    });
    return res.status(500).json({ error: 'No se pudo enviar el diagnóstico. Inténtalo de nuevo.' });
  }
}

function escapeHtml(str: string): string {
  return str.replace(/[&<>"']/g, (c) =>
    ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]!,
  );
}
