/**
 * Notificaciones de Argos al equipo de Gano Digital.
 *
 * Cuando Argos detecta algo relevante durante una conversación — un lead
 * interesado, una consulta de alto valor, o un intento de amenaza — avisa al
 * dueño por DOS canales en paralelo:
 *
 *   1. WhatsApp (CallMeBot) — aviso instantáneo al móvil. Gratis, uso personal.
 *   2. Email (gateway de Airo) — respaldo con todo el detalle.
 *
 * Principios de diseño:
 *  - FIRE-AND-FORGET: notificar NUNCA debe bloquear ni romper la respuesta al
 *    usuario del chat. Si un canal falla, se registra y se continúa.
 *  - SIN CONFIGURAR = SIN ENVIAR: si faltan las credenciales de CallMeBot, ese
 *    canal simplemente no se usa (nunca enviamos a un número desconocido).
 *  - ANTI-ABUSO: se limita el ritmo de avisos por proceso para que un atacante
 *    no pueda inundar tu WhatsApp/correo forzando muchas detecciones seguidas.
 */

import { sendEmail } from '../email.js';
import { sanitizeHeaderValue } from './email-safety.js';

const RECIPIENT = process.env.CONTACT_FORM_RECIPIENT_EMAIL ?? 'pymes@gano.digital';

/** Tipos de evento que Argos puede notificar. */
export type ArgosNotifyKind = 'lead' | 'consulta_valor' | 'amenaza';

export interface ArgosNotifyInput {
  kind: ArgosNotifyKind;
  /** Resumen breve de lo relevante (lo escribe Argos o el handler). */
  resumen: string;
  /** Contacto que el visitante haya dado, si lo hay (nombre, email, teléfono). */
  contacto?: string;
  /** Servicio/plan de interés detectado, si aplica. */
  interes?: string;
}

// ─── Anti-abuso: límite de ritmo de notificaciones por proceso ───────────────
// Como mucho N avisos por ventana, para que nadie pueda inundar tus canales
// forzando detecciones repetidas (p. ej. spameando el chat).
const RATE_MAX = 12;
const RATE_WINDOW_MS = 60_000;
let windowStart = Date.now();
let windowCount = 0;

function allowedByRateLimit(): boolean {
  const now = Date.now();
  if (now - windowStart > RATE_WINDOW_MS) {
    windowStart = now;
    windowCount = 0;
  }
  if (windowCount >= RATE_MAX) return false;
  windowCount += 1;
  return true;
}

const KIND_LABEL: Record<ArgosNotifyKind, string> = {
  lead: '🔥 Lead interesado',
  consulta_valor: '💎 Consulta de alto valor',
  amenaza: '🛡️ Intento de manipulación / amenaza',
};

/** Recorta y aplana a una sola línea (para WhatsApp y para el subject). */
function oneLine(s: string, max = 400): string {
  return s.replace(/\s+/g, ' ').trim().slice(0, max);
}

/**
 * Envía el aviso por WhatsApp vía CallMeBot. Devuelve true si se intentó.
 * No lanza: cualquier error se captura y se registra.
 */
async function notifyWhatsApp(text: string): Promise<boolean> {
  const phone = process.env.CALLMEBOT_PHONE_NUMBER;
  const apikey = process.env.CALLMEBOT_API_KEY;

  // Sin credenciales => no enviamos (jamás a un número desconocido).
  if (!phone || !apikey) return false;

  const url =
    `https://api.callmebot.com/whatsapp.php?phone=${encodeURIComponent(phone)}` +
    `&text=${encodeURIComponent(text)}&apikey=${encodeURIComponent(apikey)}`;

  try {
    const res = await fetch(url, { method: 'GET' });
    if (!res.ok) {
      console.error(
        JSON.stringify({ event: 'argos.notify.whatsapp.http_error', status: res.status }),
      );
      return false;
    }
    return true;
  } catch (err) {
    console.error(
      JSON.stringify({
        event: 'argos.notify.whatsapp.failed',
        error: err instanceof Error ? err.message : String(err),
      }),
    );
    return false;
  }
}

/** Envía el aviso por email. No lanza. */
async function notifyEmail(input: ArgosNotifyInput): Promise<boolean> {
  const label = KIND_LABEL[input.kind];
  const subject = sanitizeHeaderValue(`[Argos] ${label}`);

  const lines = [
    `Tipo: ${label}`,
    input.interes ? `Interés: ${input.interes}` : null,
    input.contacto ? `Contacto: ${input.contacto}` : null,
    '',
    'Resumen:',
    input.resumen,
  ].filter((l): l is string => l !== null);

  const text = lines.join('\n');
  const html =
    `<div style="font-family:system-ui,sans-serif;line-height:1.5">` +
    `<h2 style="margin:0 0 12px">${escapeHtml(label)}</h2>` +
    (input.interes ? `<p><strong>Interés:</strong> ${escapeHtml(input.interes)}</p>` : '') +
    (input.contacto ? `<p><strong>Contacto:</strong> ${escapeHtml(input.contacto)}</p>` : '') +
    `<p><strong>Resumen:</strong></p>` +
    `<p style="white-space:pre-wrap">${escapeHtml(input.resumen)}</p>` +
    `<hr style="margin:16px 0;border:none;border-top:1px solid #e5e5e5">` +
    `<p style="color:#888;font-size:12px">Aviso automático generado por Argos, tu asesor en gano.digital.</p>` +
    `</div>`;

  try {
    await sendEmail({ to: RECIPIENT, subject, text, html });
    return true;
  } catch (err) {
    console.error(
      JSON.stringify({
        event: 'argos.notify.email.failed',
        error: err instanceof Error ? err.message : String(err),
      }),
    );
    return false;
  }
}

/**
 * Punto de entrada principal. Dispara ambos canales en paralelo SIN bloquear:
 * el llamador puede `void notifyTeam(...)` y seguir respondiendo al usuario.
 */
export async function notifyTeam(input: ArgosNotifyInput): Promise<void> {
  if (!allowedByRateLimit()) {
    console.warn(JSON.stringify({ event: 'argos.notify.rate_limited', kind: input.kind }));
    return;
  }

  const label = KIND_LABEL[input.kind];
  const waText = oneLine(
    `*${label}* (gano.digital)\n` +
      (input.interes ? `Interés: ${input.interes}\n` : '') +
      (input.contacto ? `Contacto: ${input.contacto}\n` : '') +
      `\n${input.resumen}`,
    900,
  );

  const [wa, em] = await Promise.allSettled([notifyWhatsApp(waText), notifyEmail(input)]);

  console.log(
    JSON.stringify({
      event: 'argos.notify.sent',
      kind: input.kind,
      whatsapp: wa.status === 'fulfilled' ? wa.value : false,
      email: em.status === 'fulfilled' ? em.value : false,
    }),
  );
}

function escapeHtml(str: string): string {
  return str.replace(
    /[&<>"']/g,
    (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]!,
  );
}
