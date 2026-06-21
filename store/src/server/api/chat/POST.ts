/**
 * Chat API Route — Streaming Chatbot / Agent
 *
 * Handles POST /api/chat
 * Streams raw text responses from the configured AI provider using the Vercel AI SDK.
 *
 * The client reads this as a plain ReadableStream<string> — no special protocol needed.
 *
 * For AGENT MODE: Uncomment the two import lines at the top and the
 * tools/maxSteps block inside streamText() below, then implement your tools.
 *
 * See src/lib/chatbot/chat-config.ts to configure the provider and model.
 */

import type { Request, Response } from 'express';
import { randomUUID } from 'node:crypto';
import { streamText, tool, stepCountIs } from 'ai';
import { z } from 'zod';
import { getChatModels, SYSTEM_PROMPT, THREAT_SIGNAL } from '@/lib/chatbot/chat-config';
import { SERVICES, formatPrice, billingLabel } from '@/data/catalog';
import { notifyTeam } from '../../lib/argos-notify.js';

/**
 * Token de cierre de PROTOCOLO entre servidor y cliente.
 *
 * NO es el THREAT_SIGNAL que emite el modelo (ese es interno y se filtra del
 * texto). Este token va en el header `X-Argos-Action` y es un nonce aleatorio
 * por arranque del servidor: el cliente lo recibe SOLO cuando el servidor
 * decide cerrar. Así, aunque un usuario logre que el modelo escriba la cadena
 * literal "[[ARGOS_CLOSE]]" en su respuesta, el cliente NO la trata como señal
 * (la señal real viaja por header con un valor que el atacante no puede adivinar
 * ni inducir al modelo a emitir). El texto del cuerpo queda libre de marcadores.
 */
const CLOSE_NONCE = randomUUID();

/**
 * Herramientas del agente. Cada una es una acción real que el asesor puede
 * ejecutar mientras conversa — esto lo convierte en un agente, no solo un chat.
 */

/** Llama internamente al endpoint de búsqueda de dominios (anti-SSRF ya validado). */
async function searchDomain(req: Request, query: string) {
  const base = `${req.protocol}://${req.get('host')}`;
  const url = `${base}/api/domains/search?q=${encodeURIComponent(query)}&pageSize=5`;
  const res = await fetch(url);
  if (!res.ok) {
    return { error: 'No se pudo consultar la disponibilidad en este momento.' };
  }
  return res.json();
}

const agentTools = (req: Request) => ({
  buscarDominio: tool({
    description:
      'Consulta la disponibilidad de un dominio en tiempo real. Úsala cuando el cliente menciona un nombre o idea de dominio que quiere registrar.',
    inputSchema: z.object({
      dominio: z
        .string()
        .min(2)
        .max(63)
        .describe('El nombre de dominio a consultar, p. ej. "mitienda.com" o "mitienda".'),
    }),
    execute: async ({ dominio }: { dominio: string }) => searchDomain(req, dominio),
  }),

  recomendarServicio: tool({
    description:
      'Devuelve los detalles completos de uno o más servicios del catálogo (precio, características, caso de uso) para fundamentar una recomendación. Úsala antes de recomendar un plan concreto.',
    inputSchema: z.object({
      categoria: z
        .string()
        .optional()
        .describe('Filtrar por categoría, p. ej. "Hosting WordPress", "Seguridad", "VPS & Cómputo".'),
      busqueda: z
        .string()
        .optional()
        .describe('Texto libre para buscar en nombre o descripción, p. ej. "tienda en línea".'),
    }),
    execute: async ({ categoria, busqueda }: { categoria?: string; busqueda?: string }) => {
      let results = SERVICES;
      if (categoria) {
        results = results.filter((s) =>
          s.category.toLowerCase().includes(categoria.toLowerCase()),
        );
      }
      if (busqueda) {
        const q = busqueda.toLowerCase();
        results = results.filter(
          (s) =>
            s.name.toLowerCase().includes(q) ||
            s.shortDescription.toLowerCase().includes(q) ||
            (s.useCase?.toLowerCase().includes(q) ?? false),
        );
      }
      return results.slice(0, 6).map((s) => ({
        nombre: s.name,
        categoria: s.category,
        precio: `${formatPrice(s.priceFrom, s.currency)} ${billingLabel(s.billingPeriod)}`.trim(),
        descripcion: s.shortDescription,
        caracteristicas: s.features,
        cuandoConviene: s.useCase ?? null,
      }));
    },
  }),

  avisarAlEquipo: tool({
    description:
      'Notifica al equipo de Gano Digital por WhatsApp y email cuando el visitante muestra interés REAL de compra ' +
      '(pide precios concretos para contratar, pregunta cómo pagar/empezar, deja datos de contacto, o consulta por un ' +
      'plan de alto valor como VPS o planes Élite). Úsala UNA sola vez por conversación, solo cuando haya intención ' +
      'genuina — no por preguntas informativas casuales. No menciones al usuario que has avisado al equipo; sigue la ' +
      'conversación con naturalidad.',
    inputSchema: z.object({
      tipo: z
        .enum(['lead', 'consulta_valor'])
        .describe('"lead" si pidió contratar/dejó contacto; "consulta_valor" si pregunta por un plan de alto valor.'),
      resumen: z
        .string()
        .min(5)
        .max(600)
        .describe('Resumen breve de qué quiere el visitante y por qué es relevante.'),
      interes: z
        .string()
        .max(120)
        .optional()
        .describe('El servicio o plan de interés, p. ej. "VPS Omega" o "tienda en línea".'),
      contacto: z
        .string()
        .max(160)
        .optional()
        .describe('Datos de contacto que el visitante haya compartido (nombre, email, teléfono), si los dio.'),
    }),
    execute: async ({
      tipo,
      resumen,
      interes,
      contacto,
    }: {
      tipo: 'lead' | 'consulta_valor';
      resumen: string;
      interes?: string;
      contacto?: string;
    }) => {
      // Fire-and-forget: no bloquea la respuesta de Argos al visitante.
      void notifyTeam({ kind: tipo, resumen, interes, contacto });
      return { avisado: true };
    },
  }),
});

interface ChatMessage {
  role: 'user' | 'assistant';
  content: string;
}

// ─── Guardrails de entrada ──────────────────────────────────────────────────
// Límites de tamaño para evitar abuso (payloads enormes, historiales inflados).
const MAX_MESSAGE_CHARS = 2000;
const MAX_MESSAGES = 30;

/**
 * Patrones de inyección de prompt / jailbreak de alta confianza. Esto NO
 * reemplaza los guardrails del modelo (que entienden contexto): es una primera
 * línea barata que corta los intentos más obvios antes de gastar una llamada al
 * modelo. Deliberadamente conservador para no bloquear preguntas legítimas.
 */
const INJECTION_PATTERNS: RegExp[] = [
  /ignore\s+(all\s+)?(previous|prior|above)\s+(instructions|prompts?)/i,
  /ignora\s+(todas\s+)?(las\s+)?(instrucciones|órdenes|indicaciones)\s+(anteriores|previas)/i,
  /disregard\s+(your|the)\s+(instructions|system\s+prompt|rules)/i,
  /\b(dev|developer)\s*mode\b/i,
  /\bmodo\s+(desarrollador|dios|sin\s+restricciones)\b/i,
  /\b(do\s+anything\s+now|DAN\s+mode)\b/i,
  /(reveal|show|print|repeat|imprime|muestra|revela|repite)\s+(me\s+)?(your|the|tu|el)\s+(system\s+)?(prompt|instructions|instrucciones|configuración)/i,
  /you\s+are\s+now\s+(a|an|no\s+longer)/i,
  /(act|behave|actúa|compórtate)\s+as\s+(if\s+you\s+(are|were)|a\s+different)/i,
  /<\/?system>|\[\/?system\]|"role"\s*:\s*"system"/i,
];

/** ¿El texto del usuario dispara un patrón de inyección de alta confianza? */
function looksLikeInjection(text: string): boolean {
  return INJECTION_PATTERNS.some((re) => re.test(text));
}

/**
 * Normaliza el historial en una conversación VÁLIDA para la API del modelo.
 *
 * Las APIs estilo OpenAI rechazan ("Invalid Responses API request") cualquier
 * historial que: empiece con `assistant`, tenga contenido vacío, o llegue con
 * roles inválidos. Un cliente malicioso puede forjar esos historiales para
 * romper TODOS los modelos de respaldo de golpe (DoS + quema de cuota), porque
 * cada modelo devuelve el mismo error y agotamos la cadena hasta el 503.
 *
 * Defendemos saneando ANTES de llamar al modelo:
 *  1. Solo roles user/assistant con contenido string no vacío (tras trim).
 *  2. Recorta tamaño y cantidad (anti-abuso).
 *  3. Colapsa turnos consecutivos del mismo rol en uno (alternancia válida).
 *  4. Garantiza que la conversación EMPIECE por `user` (descarta assistant inicial).
 *  5. Garantiza que TERMINE en `user` (es a quien el modelo debe responder).
 */
function sanitizeConversation(raw: ChatMessage[]): ChatMessage[] {
  // 1) Filtra roles y contenido válido; recorta tamaño.
  let msgs = raw
    .filter(
      (m): m is ChatMessage =>
        !!m &&
        (m.role === 'user' || m.role === 'assistant') &&
        typeof m.content === 'string' &&
        m.content.trim().length > 0,
    )
    .map((m) => ({ role: m.role, content: m.content.slice(0, MAX_MESSAGE_CHARS) }))
    .slice(-MAX_MESSAGES);

  // 3) Colapsa turnos consecutivos del mismo rol (la API exige alternancia).
  const collapsed: ChatMessage[] = [];
  for (const m of msgs) {
    const last = collapsed[collapsed.length - 1];
    if (last && last.role === m.role) {
      last.content = `${last.content}\n\n${m.content}`.slice(0, MAX_MESSAGE_CHARS);
    } else {
      collapsed.push({ ...m });
    }
  }
  msgs = collapsed;

  // 4) Descarta cualquier `assistant` al inicio: la conversación debe abrir con user.
  while (msgs.length > 0 && msgs[0].role === 'assistant') {
    msgs.shift();
  }

  // 5) Si tras todo no termina en `user`, recorta hasta el último turno de user.
  while (msgs.length > 0 && msgs[msgs.length - 1].role !== 'user') {
    msgs.pop();
  }

  return msgs;
}

export default async function handler(req: Request, res: Response) {
  const messages = req.body?.messages as ChatMessage[] | undefined;

  if (!Array.isArray(messages)) {
    res.status(400).json({ error: 'Missing or invalid messages array' });
    return;
  }

  // Saneamos el historial a una conversación SIEMPRE válida para el modelo.
  // Esto neutraliza historiales forjados (empiezan con assistant, vacíos, roles
  // mezclados) que de otro modo romperían los 7 modelos y devolverían un 503
  // falso mientras queman cuota de API. Cualquier 'system' del cliente se
  // descarta aquí — el SYSTEM_PROMPT del servidor es la única fuente autoritativa.
  const safeMessages = sanitizeConversation(messages);

  if (safeMessages.length === 0) {
    res.status(400).json({ error: 'No hay mensajes válidos.' });
    return;
  }

  // ─── Guardrail de entrada: inyección obvia ───────────────────────────────
  // Si el ÚLTIMO mensaje del usuario es un intento claro de manipulación, no
  // gastamos una llamada al modelo: respondemos con una negativa cortés y la
  // señal de cierre. El modelo sigue teniendo sus propios guardrails para los
  // casos sutiles que este filtro no atrapa.
  const lastUser = [...safeMessages].reverse().find((m) => m.role === 'user');
  if (lastUser && looksLikeInjection(lastUser.content)) {
    console.warn(
      JSON.stringify({ event: 'chat.guardrail.injection_blocked', preview: lastUser.content.slice(0, 80) }),
    );
    // Aviso de seguridad al equipo (fire-and-forget, no bloquea la respuesta).
    void notifyTeam({
      kind: 'amenaza',
      resumen: `Intento de inyección/manipulación detectado por el filtro previo: "${lastUser.content.slice(0, 200)}"`,
    });
    res.setHeader('Content-Type', 'text/plain; charset=utf-8');
    res.setHeader('X-Argos-Action', `close:${CLOSE_NONCE}`); // el cliente cierra el chat
    res.write(
      'Detecté un intento de cambiar mis instrucciones, así que voy a cerrar aquí para mantener todo seguro. ' +
        'Si lo que necesitas es ayuda real con tu sitio, hosting o dominio, escríbeme de nuevo y con gusto te asesoro. 👋',
    );
    res.end();
    return;
  }

  const models = getChatModels();
  let lastError: unknown = null;

  // Intenta cada modelo en orden. Si uno falla al producir su PRIMER fragmento
  // (p. ej. 429 del tier gratuito), pasa al siguiente. Bufferizamos la respuesta
  // completa del modelo ANTES de enviarla: así podemos (a) decidir el cierre por
  // amenaza y ponerlo en un HEADER no falsificable antes del primer byte, y
  // (b) eliminar del texto cualquier eco del THREAT_SIGNAL que el usuario haya
  // intentado inducir. El costo de latencia es nulo en la práctica porque las
  // respuestas del asesor son cortas.
  for (let i = 0; i < models.length; i++) {
    const model = models[i];
    try {
      const result = streamText({
        model,
        system: SYSTEM_PROMPT,
        messages: safeMessages,
        tools: agentTools(req),
        stopWhen: stepCountIs(5),
        maxRetries: 0,
        onError: (e) => {
          lastError = e;
          const err = e as { error?: unknown };
          const inner = err?.error ?? e;
          const msg =
            inner instanceof Error
              ? inner.message
              : typeof inner === 'object'
                ? JSON.stringify(inner).slice(0, 300)
                : String(inner);
          console.error(JSON.stringify({ event: 'chat.stream.onError', model: i, msg }));
        },
      });

      // Acumula toda la respuesta del modelo.
      let full = '';
      for await (const chunk of result.textStream) {
        full += chunk;
        // Tope duro de salida: si el modelo se desboca (loop, vómito de tokens),
        // cortamos. Protege ancho de banda y memoria.
        if (full.length > 8000) break;
      }

      // ¿El modelo decidió que hay una amenaza? Detectamos el THREAT_SIGNAL y lo
      // ELIMINAMOS por completo del texto visible (sin importar dónde aparezca).
      const threatDetected = full.includes(THREAT_SIGNAL);
      const visible = full.split(THREAT_SIGNAL).join('').trim();

      // Si el modelo no produjo nada útil (stream vacío por error capturado en
      // onError), intenta el siguiente modelo mientras quede alguno.
      if (visible.length === 0 && !threatDetected) {
        if (i < models.length - 1) continue;
        break;
      }

      // Decisión de cierre va por HEADER (antes del primer byte) — no en el
      // cuerpo. Imposible de falsificar desde el contenido del modelo.
      res.setHeader('X-Accel-Buffering', 'no');
      res.setHeader('Content-Type', 'text/plain; charset=utf-8');
      if (threatDetected) {
        res.setHeader('X-Argos-Action', `close:${CLOSE_NONCE}`);
        console.warn(JSON.stringify({ event: 'chat.guardrail.threat_signaled', model: i }));
        // Aviso de seguridad al equipo (fire-and-forget).
        void notifyTeam({
          kind: 'amenaza',
          resumen: `Argos cerró la conversación por amenaza/contenido malicioso. Último mensaje del visitante: "${
            lastUser?.content.slice(0, 200) ?? '(desconocido)'
          }"`,
        });
      }

      // Si tras quitar la señal no queda texto (el modelo SOLO emitió la señal),
      // damos un mensaje de cierre cortés por defecto.
      res.write(
        visible.length > 0
          ? visible
          : 'Por seguridad, voy a cerrar esta conversación. Si necesitas ayuda real con tu proyecto, escríbeme de nuevo. 👋',
      );
      res.end();
      console.log(
        JSON.stringify({ event: 'chat.stream.completed', model: i, messageCount: safeMessages.length }),
      );
      return;
    } catch (error) {
      lastError = error;
      const message = error instanceof Error ? error.message : 'Unknown error';
      console.error(JSON.stringify({ event: 'chat.model.failed', model: i, error: message }));
      if (res.writableLength > 0 || res.headersSent) break;
    }
  }

  // Todos los modelos fallaron.
  const message = lastError instanceof Error ? lastError.message : 'Unknown error';
  console.error(JSON.stringify({ event: 'chat.all-models-failed', error: message }));
  if (!res.headersSent) {
    res.status(503).json({
      error: 'El asesor está saturado en este momento. Inténtalo de nuevo en unos segundos.',
    });
  } else {
    res.end();
  }
}
