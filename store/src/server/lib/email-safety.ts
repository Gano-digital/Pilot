/**
 * Saneamiento de campos que terminan en CABECERAS de correo (Subject, Reply-To).
 *
 * Defensa en profundidad: aunque el gateway de Airo autora el mensaje
 * RFC-5322 a partir de un payload JSON (y por tanto neutraliza el inyectado
 * de cabeceras en su propia capa), NUNCA debemos depender de que un servicio
 * aguas abajo limpie nuestra entrada. Cualquier dato controlado por el
 * usuario que vaya a un campo que pueda convertirse en una cabecera —
 * `subject` y `replyTo` en nuestros handlers — se limpia aquí ANTES de salir
 * de nuestro código.
 *
 * El vector clásico (CRLF / header injection) consiste en colar un salto de
 * línea seguido de una cabecera falsa, p. ej.:
 *
 *   nombre = "Juan\r\nBcc: victima@dominio.com"
 *
 * Si ese valor llegara crudo a la línea Subject de un mensaje SMTP, el
 * atacante añadiría destinatarios ocultos o reescribiría cabeceras. Eliminar
 * CR, LF (y sus variantes Unicode de salto de línea) cierra el vector por
 * completo.
 */

// CR, LF, NEL, line/paragraph separator, vertical tab, form feed.
// Cubrimos también las variantes Unicode porque algunos parsers laxos las
// tratan como fin de línea.
const LINE_BREAKS = /[\r\n\u0085\u2028\u2029\u000B\u000C]+/g;

/**
 * Limpia un valor de una sola línea destinado a una cabecera de correo:
 * colapsa cualquier salto de línea a un espacio, recorta y limita longitud.
 * Una cabecera nunca debe contener saltos de línea sin plegar (folding),
 * así que la regla más segura aquí es: no hay saltos de línea, punto.
 */
export function sanitizeHeaderValue(value: string, maxLen = 300): string {
  return value
    .replace(LINE_BREAKS, ' ')
    .replace(/\s{2,}/g, ' ')
    .trim()
    .slice(0, maxLen);
}

/**
 * Valida que una dirección de correo sea segura para usar como Reply-To.
 * Devuelve la dirección saneada si es válida, o `undefined` si no lo es
 * (en cuyo caso el llamador debe omitir replyTo en lugar de enviar basura).
 *
 * El formato ya se valida con regex en cada handler; esto es la última
 * barrera: garantiza que NINGÚN salto de línea sobreviva hasta el header,
 * incluso si la validación de formato cambiara en el futuro.
 */
export function sanitizeReplyTo(email: string): string | undefined {
  const cleaned = sanitizeHeaderValue(email, 254);
  // Una dirección con espacios internos (resultado de plegar un salto de
  // línea) ya no es una dirección válida — la rechazamos por completo.
  if (/\s/.test(cleaned)) return undefined;
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(cleaned)) return undefined;
  return cleaned;
}
