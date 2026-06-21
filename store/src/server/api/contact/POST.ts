import type { Request, Response } from 'express';
import { sendEmail } from '../../email.js';
import { sanitizeHeaderValue, sanitizeReplyTo } from '../../lib/email-safety.js';

const RECIPIENT = process.env.CONTACT_FORM_RECIPIENT_EMAIL ?? 'pymes@gano.digital';

const PROJECT_TYPE_LABELS: Record<string, string> = {
  hosting: 'Hosting WordPress',
  dominio: 'Dominio y correo profesional',
  seguridad: 'Seguridad, SSL y backups',
  vps: 'VPS o servidor a medida',
  desarrollo: 'Desarrollo web a medida',
  diagnostico: 'Diagnóstico de infraestructura',
  migracion: 'Migración desde otro proveedor',
  otro: 'Otra consulta',
};

const BUDGET_LABELS: Record<string, string> = {
  'menos50k': 'Menos de $50.000 / mes',
  '50k150k': '$50.000 – $150.000 / mes',
  '150k500k': '$150.000 – $500.000 / mes',
  'proyecto': 'Proyecto a medida (pago único)',
  'nose': 'Aún no lo sé',
};

function sanitize(value: unknown, maxLen = 2000): string {
  if (typeof value !== 'string') return '';
  return value.trim().slice(0, maxLen);
}

export default async function handler(req: Request, res: Response) {
  const name        = sanitize(req.body?.name, 100);
  const email       = sanitize(req.body?.email, 254);
  const projectType = sanitize(req.body?.projectType, 50);
  const budget      = sanitize(req.body?.budget, 50);
  const message     = sanitize(req.body?.message, 2000);

  // Basic server-side validation
  if (name.length < 2) {
    return res.status(400).json({ error: 'Nombre inválido.' });
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email)) {
    return res.status(400).json({ error: 'Correo inválido.' });
  }
  if (!projectType || !PROJECT_TYPE_LABELS[projectType]) {
    return res.status(400).json({ error: 'Tipo de proyecto inválido.' });
  }
  if (message.length < 10) {
    return res.status(400).json({ error: 'Mensaje demasiado corto.' });
  }

  const projectLabel = PROJECT_TYPE_LABELS[projectType] ?? projectType;
  const budgetLabel  = BUDGET_LABELS[budget] ?? (budget || 'No especificado');

  const textBody = [
    `Nuevo mensaje desde el formulario de contacto de gano.digital`,
    ``,
    `Nombre:           ${name}`,
    `Correo:           ${email}`,
    `Tipo de proyecto: ${projectLabel}`,
    `Presupuesto:      ${budgetLabel}`,
    ``,
    `Mensaje:`,
    message,
    ``,
    `---`,
    `Responde directamente a este correo para contactar al cliente.`,
  ].join('\n');

  const htmlBody = `
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#0A0A0A;font-family:system-ui,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#0A0A0A;padding:40px 20px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#1A1A1A;border-radius:8px;overflow:hidden;max-width:600px;width:100%;">
        <!-- Header -->
        <tr>
          <td style="background:#D97E3A;padding:24px 32px;">
            <p style="margin:0;color:#0A0A0A;font-size:11px;letter-spacing:0.2em;text-transform:uppercase;font-weight:600;">Gano Digital</p>
            <h1 style="margin:8px 0 0;color:#0A0A0A;font-size:22px;font-weight:700;letter-spacing:-0.02em;">Nuevo mensaje de contacto</h1>
          </td>
        </tr>
        <!-- Fields -->
        <tr>
          <td style="padding:32px;">
            <table width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td style="padding-bottom:20px;border-bottom:1px solid #2A2A2A;">
                  <p style="margin:0 0 4px;color:#888;font-size:11px;text-transform:uppercase;letter-spacing:0.15em;">Nombre</p>
                  <p style="margin:0;color:#F5F5F5;font-size:16px;">${escapeHtml(name)}</p>
                </td>
              </tr>
              <tr>
                <td style="padding:20px 0;border-bottom:1px solid #2A2A2A;">
                  <p style="margin:0 0 4px;color:#888;font-size:11px;text-transform:uppercase;letter-spacing:0.15em;">Correo</p>
                  <p style="margin:0;"><a href="mailto:${escapeHtml(email)}" style="color:#D97E3A;font-size:16px;text-decoration:none;">${escapeHtml(email)}</a></p>
                </td>
              </tr>
              <tr>
                <td style="padding:20px 0;border-bottom:1px solid #2A2A2A;">
                  <p style="margin:0 0 4px;color:#888;font-size:11px;text-transform:uppercase;letter-spacing:0.15em;">Tipo de proyecto</p>
                  <p style="margin:0;color:#F5F5F5;font-size:16px;">${escapeHtml(projectLabel)}</p>
                </td>
              </tr>
              <tr>
                <td style="padding:20px 0;border-bottom:1px solid #2A2A2A;">
                  <p style="margin:0 0 4px;color:#888;font-size:11px;text-transform:uppercase;letter-spacing:0.15em;">Presupuesto estimado</p>
                  <p style="margin:0;color:#F5F5F5;font-size:16px;">${escapeHtml(budgetLabel)}</p>
                </td>
              </tr>
              <tr>
                <td style="padding:20px 0;">
                  <p style="margin:0 0 8px;color:#888;font-size:11px;text-transform:uppercase;letter-spacing:0.15em;">Mensaje</p>
                  <p style="margin:0;color:#F5F5F5;font-size:15px;line-height:1.7;white-space:pre-wrap;">${escapeHtml(message)}</p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <!-- CTA -->
        <tr>
          <td style="padding:0 32px 32px;">
            <a href="mailto:${escapeHtml(email)}" style="display:inline-block;background:#D97E3A;color:#0A0A0A;font-size:13px;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;text-decoration:none;padding:14px 28px;border-radius:4px;">
              Responder a ${escapeHtml(name)}
            </a>
          </td>
        </tr>
        <!-- Footer -->
        <tr>
          <td style="padding:20px 32px;border-top:1px solid #2A2A2A;">
            <p style="margin:0;color:#555;font-size:12px;">Este mensaje fue enviado desde el formulario de contacto de <a href="https://gano.digital" style="color:#D97E3A;text-decoration:none;">gano.digital</a></p>
          </td>
        </tr>
      </table>
    </td></tr>
  </table>
</body>
</html>`;

  try {
    // Subject y Reply-To van a CABECERAS de correo: se sanean contra CRLF
    // injection antes de salir de nuestro código (defensa en profundidad,
    // no dependemos de que el gateway lo haga). Reply-To se omite si el
    // saneo lo invalida — preferible a enviar una cabecera corrupta.
    const safeSubject = sanitizeHeaderValue(
      `[Gano Digital] Nuevo contacto: ${name} — ${projectLabel}`,
    );
    const safeReplyTo = sanitizeReplyTo(email);

    await sendEmail({
      to: RECIPIENT,
      subject: safeSubject,
      text: textBody,
      html: htmlBody,
      ...(safeReplyTo ? { replyTo: safeReplyTo } : {}),
    });

    return res.status(200).json({ ok: true });
  } catch (err) {
    console.error('contact.form.send-failed', {
      error: err instanceof Error ? err.message : String(err),
    });
    return res.status(500).json({ error: 'No se pudo enviar el mensaje. Inténtalo de nuevo.' });
  }
}

function escapeHtml(str: string): string {
  return str.replace(/[&<>"']/g, (c) =>
    ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]!,
  );
}
