import { useState, useRef, useEffect, type FormEvent } from 'react';
import { X, Send } from 'lucide-react';
import { useLocale } from '@/hooks/useLocale';
import { ArgosFace } from '@/components/ArgosFace';

/**
 * Argos — asistente con IA y personalidad propia, disponible en todo el sitio.
 *
 * Burbuja flotante con el rostro robótico animado de Argos (ojos blancos que
 * siguen el cursor y parpadean). Abre un panel de chat con streaming en vivo
 * contra POST /api/chat. Conoce el catálogo real de Gano Digital, asesora con
 * criterio, y puede DENEGAR solicitudes y CERRAR el chat ante amenazas.
 *
 * SSR-safe: arranca cerrado y todo el fetch ocurre tras interacción del usuario.
 * Estética terracota, temas claro/oscuro vía variables CSS, copy bilingüe.
 */

interface Message {
  id: string;
  role: 'user' | 'assistant';
  content: string;
}

const COPY = {
  es: {
    open: 'Abrir a Argos, asistente con IA',
    title: 'Argos',
    subtitle: 'IA de Gano · siempre atento',
    greeting:
      '¡Hola! Soy **Argos** 👀, el asistente de Gano Digital. Cuéntame qué necesitas —un sitio, un dominio, más velocidad o seguridad— y te catapulto a la mejor opción.',
    placeholder: 'Escríbele a Argos…',
    send: 'Enviar',
    error: 'Algo falló. Inténtalo de nuevo en un momento.',
    closed: 'Argos cerró esta conversación. Recárgala para empezar de nuevo.',
    reopen: 'Iniciar nueva conversación',
    starters: [
      'Necesito un sitio para mi negocio',
      '¿Está disponible mi dominio?',
      'Mi web va lenta, ¿qué hago?',
    ],
  },
  en: {
    open: 'Open Argos, AI assistant',
    title: 'Argos',
    subtitle: "Gano's AI · always watching",
    greeting:
      "Hi! I'm **Argos** 👀, Gano Digital's assistant. Tell me what you need —a website, a domain, more speed or security— and I'll catapult you to the best option.",
    placeholder: 'Message Argos…',
    send: 'Send',
    error: 'Something went wrong. Please try again in a moment.',
    closed: 'Argos closed this conversation. Reload it to start over.',
    reopen: 'Start a new conversation',
    starters: [
      'I need a website for my business',
      'Is my domain available?',
      'My site is slow, what can I do?',
    ],
  },
} as const;

export function AsesorWidget() {
  const { locale } = useLocale();
  const t = COPY[locale === 'en' ? 'en' : 'es'];

  const [open, setOpen] = useState(false);
  const [messages, setMessages] = useState<Message[]>([]);
  const [input, setInput] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  // Cuando Argos detecta una amenaza, cierra la conversación: se bloquea la
  // entrada y se muestra un aviso con opción de reiniciar.
  const [closedByArgos, setClosedByArgos] = useState(false);
  const bottomRef = useRef<HTMLDivElement>(null);
  const inputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    if (open) {
      bottomRef.current?.scrollIntoView({ behavior: 'smooth' });
    }
  }, [messages, open]);

  useEffect(() => {
    if (open && !closedByArgos) inputRef.current?.focus();
  }, [open, closedByArgos]);

  function resetConversation() {
    setMessages([]);
    setClosedByArgos(false);
    setError(null);
    setInput('');
  }

  async function sendMessage(text: string) {
    const trimmed = text.trim();
    if (!trimmed || isLoading || closedByArgos) return;

    const userMessage: Message = { id: `user-${Date.now()}`, role: 'user', content: trimmed };
    const assistantId = `assistant-${Date.now()}`;

    setMessages((prev) => [
      ...prev,
      userMessage,
      { id: assistantId, role: 'assistant', content: '' },
    ]);
    setInput('');
    setIsLoading(true);
    setError(null);

    try {
      const history = [...messages, userMessage].map((m) => ({
        role: m.role,
        content: m.content,
      }));

      const response = await fetch('/api/chat', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ messages: history }),
      });

      if (!response.ok || !response.body) {
        throw new Error(`HTTP ${response.status}`);
      }

      // Argos cierra el chat por amenaza SOLO vía header de protocolo
      // (X-Argos-Action: close:<nonce>). No confiamos en marcadores dentro del
      // texto: un usuario podría inducir al modelo a escribirlos. El header lo
      // pone el servidor y su valor no es adivinable ni inducible.
      const closeAction = response.headers.get('X-Argos-Action') ?? '';
      const shouldClose = closeAction.startsWith('close:');

      const reader = response.body.getReader();
      const decoder = new TextDecoder();
      let full = '';

      for (let result = await reader.read(); !result.done; result = await reader.read()) {
        full += decoder.decode(result.value, { stream: true });
      }

      // El servidor responde bufferizado (para decidir el cierre por header
      // antes del primer byte). Recreamos la sensación "viva" de Argos revelando
      // el texto de forma progresiva en el cliente.
      await typeOut(full, (partial) => {
        setMessages((prev) =>
          prev.map((m) => (m.id === assistantId ? { ...m, content: partial } : m)),
        );
      });

      if (shouldClose) {
        setClosedByArgos(true);
      }
    } catch {
      setError(t.error);
      setMessages((prev) => prev.filter((m) => m.id !== assistantId));
    } finally {
      setIsLoading(false);
    }
  }

  function handleSubmit(e: FormEvent<HTMLFormElement>) {
    e.preventDefault();
    void sendMessage(input);
  }

  const lastMessage = messages[messages.length - 1];
  const showTyping =
    isLoading && lastMessage?.role === 'assistant' && lastMessage.content === '';

  return (
    <>
      {/* Burbuja flotante con el rostro de Argos */}
      {!open && (
        <button
          type="button"
          onClick={() => setOpen(true)}
          aria-label={t.open}
          className="argos-bubble fixed bottom-5 right-5 z-50 flex h-16 w-16 items-center justify-center rounded-full bg-primary text-primary-foreground shadow-lg transition-transform duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
        >
          <ArgosFace size={40} trackPointer eyeColor="hsl(var(--primary-foreground))" />
          <span className="absolute -right-0.5 -top-0.5 flex h-3.5 w-3.5">
            <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-accent opacity-75" />
            <span className="relative inline-flex h-3.5 w-3.5 rounded-full bg-accent" />
          </span>
        </button>
      )}

      {/* Panel de chat */}
      {open && (
        <div className="fixed bottom-5 right-5 z-50 flex h-[min(560px,calc(100vh-2.5rem))] w-[min(380px,calc(100vw-2.5rem))] flex-col overflow-hidden rounded-2xl border border-border bg-card shadow-2xl">
          {/* Cabecera */}
          <div className="flex items-center justify-between gap-3 border-b border-border bg-primary px-4 py-3 text-primary-foreground">
            <div className="flex items-center gap-2.5">
              <div className="flex h-10 w-10 items-center justify-center rounded-full bg-primary-foreground/15">
                <ArgosFace size={28} trackPointer eyeColor="hsl(var(--primary-foreground))" />
              </div>
              <div className="leading-tight">
                <p className="text-sm font-semibold">{t.title}</p>
                <p className="text-[11px] opacity-80">{t.subtitle}</p>
              </div>
            </div>
            <button
              type="button"
              onClick={() => setOpen(false)}
              aria-label="Cerrar"
              className="rounded-full p-1.5 transition-colors hover:bg-primary-foreground/15 focus:outline-none focus:ring-2 focus:ring-primary-foreground/40"
            >
              <X className="h-5 w-5" />
            </button>
          </div>

          {/* Mensajes */}
          <div className="flex-1 space-y-3 overflow-y-auto px-4 py-4">
            {/* Saludo inicial */}
            <Bubble role="assistant" content={t.greeting} />

            {/* Sugerencias de inicio (solo antes del primer mensaje) */}
            {messages.length === 0 && !closedByArgos && (
              <div className="flex flex-col gap-2 pt-1">
                {t.starters.map((s) => (
                  <button
                    key={s}
                    type="button"
                    onClick={() => void sendMessage(s)}
                    className="self-start rounded-full border border-border bg-muted px-3 py-1.5 text-left text-xs text-muted-foreground transition-colors hover:border-primary hover:text-foreground"
                  >
                    {s}
                  </button>
                ))}
              </div>
            )}

            {messages.map((m) => (
              <Bubble key={m.id} role={m.role} content={m.content} />
            ))}

            {showTyping && (
              <div className="flex justify-start">
                <div className="rounded-2xl rounded-tl-sm bg-muted px-4 py-3">
                  <div className="flex h-4 items-center gap-1">
                    <span className="h-2 w-2 animate-bounce rounded-full bg-muted-foreground [animation-delay:-0.3s]" />
                    <span className="h-2 w-2 animate-bounce rounded-full bg-muted-foreground [animation-delay:-0.15s]" />
                    <span className="h-2 w-2 animate-bounce rounded-full bg-muted-foreground" />
                  </div>
                </div>
              </div>
            )}

            {error && <p className="text-center text-xs text-destructive">{error}</p>}

            {/* Aviso de cierre por amenaza */}
            {closedByArgos && (
              <div className="flex flex-col items-center gap-2 pt-2 text-center">
                <p className="text-xs text-muted-foreground">{t.closed}</p>
                <button
                  type="button"
                  onClick={resetConversation}
                  className="rounded-full border border-border bg-muted px-3 py-1.5 text-xs text-foreground transition-colors hover:border-primary"
                >
                  {t.reopen}
                </button>
              </div>
            )}

            <div ref={bottomRef} />
          </div>

          {/* Entrada */}
          <form onSubmit={handleSubmit} className="flex items-center gap-2 border-t border-border p-3">
            <input
              ref={inputRef}
              value={input}
              onChange={(e) => setInput(e.target.value)}
              type="text"
              placeholder={t.placeholder}
              disabled={isLoading || closedByArgos}
              autoComplete="off"
              className="flex-1 rounded-full border border-input bg-background px-4 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring disabled:opacity-60"
            />
            <button
              type="submit"
              disabled={isLoading || closedByArgos || !input.trim()}
              aria-label={t.send}
              className="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-primary-foreground transition-opacity hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50"
            >
              <Send className="h-4 w-4" />
            </button>
          </form>
        </div>
      )}
    </>
  );
}

/**
 * Revela un texto de forma progresiva (efecto máquina de escribir) llamando a
 * `onUpdate` con porciones crecientes. Rápido para no frustrar al usuario.
 * Respeta prefers-reduced-motion: en ese caso muestra todo de una vez.
 */
async function typeOut(text: string, onUpdate: (partial: string) => void): Promise<void> {
  const reduced =
    typeof window !== 'undefined' &&
    window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;

  if (reduced || text.length === 0) {
    onUpdate(text);
    return;
  }

  const steps = Math.min(40, text.length);
  const chunk = Math.ceil(text.length / steps);
  for (let i = chunk; i < text.length; i += chunk) {
    onUpdate(text.slice(0, i));
    await new Promise((r) => setTimeout(r, 18));
  }
  onUpdate(text);
}

function Bubble({ role, content }: { role: 'user' | 'assistant'; content: string }) {
  if (!content) return null;
  const isUser = role === 'user';
  return (
    <div className={`flex ${isUser ? 'justify-end' : 'justify-start'}`}>
      <div
        className={`max-w-[82%] whitespace-pre-wrap rounded-2xl px-4 py-2.5 text-sm leading-relaxed ${
          isUser
            ? 'rounded-tr-sm bg-primary text-primary-foreground'
            : 'rounded-tl-sm bg-muted text-foreground'
        }`}
      >
        {content}
      </div>
    </div>
  );
}
