/**
 * Gano Digital — Chat IA
 * v1.4.0 — V-05 Fix: Auditoria Gano Digital, Marzo 2026.
 *   — Todas las peticiones fetch incluyen X-WP-Nonce (CSRF protection).
 *   — URLs de endpoints provienen de ganoChatConfig (wp_localize_script).
 *   — Input sanitizado antes de enviar al servidor.
 *   — Nudge proactivo reescrito: empático, sin afirmaciones alarmistas falsas.
 */

document.addEventListener('DOMContentLoaded', function () {

    // ─── CONFIG ──────────────────────────────────────────────────────────────
    // ganoChatConfig es inyectado por wp_localize_script desde functions.php
    const cfg = window.ganoChatConfig || {
        nonce:        '',
        logEndpoint:  '/wp-json/gano-agent/v1/log',
        chatEndpoint: '/wp-json/gano/v1/chat',
    };

    // ─── CONSTRUIR INTERFAZ DEL CHAT ─────────────────────────────────────────
    const chatBubble = document.createElement('div');
    chatBubble.id = 'gano-chat-bubble';
    chatBubble.setAttribute('aria-label', 'Abrir chat de soporte Gano Digital');
    chatBubble.setAttribute('role', 'button');
    chatBubble.setAttribute('tabindex', '0');
    chatBubble.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>';
    document.body.appendChild(chatBubble);

    const chatWindow = document.createElement('div');
    chatWindow.id = 'gano-chat-window';
    chatWindow.setAttribute('role', 'dialog');
    chatWindow.setAttribute('aria-label', 'Chat de soporte Gano Digital');
    chatWindow.setAttribute('aria-modal', 'true');
    chatWindow.innerHTML = `
        <div class="gano-chat-header">
            <div class="status-dot" aria-hidden="true"></div>
            <div style="color:#fff;font-weight:700;">
                Gano Agent
                <span style="color:var(--gano-chat-gold);font-size:0.7rem;margin-left:5px;">IA EXPERT</span>
            </div>
        </div>
        <div class="gano-chat-body" id="gano-chat-messages" role="log" aria-live="polite">
            <div class="chat-msg agent">¡Hola! Soy el Agente Gano. ¿En qué te ayudo con tu proyecto digital hoy?</div>
        </div>
        <div class="gano-chat-footer">
            <input type="text"
                   id="gano-chat-input"
                   placeholder="Escribe tu consulta..."
                   aria-label="Mensaje al Agente Gano"
                   maxlength="500">
            <button id="gano-chat-send" aria-label="Enviar mensaje">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(chatWindow);

    // ─── TOGGLE DE APERTURA ───────────────────────────────────────────────────
    function openChat() {
        chatWindow.classList.add('active');
        chatBubble.setAttribute('aria-expanded', 'true');
        document.getElementById('gano-chat-input').focus();
        postLog('EVENT: Chat Window Opened');
    }

    function closeChat() {
        chatWindow.classList.remove('active');
        chatBubble.setAttribute('aria-expanded', 'false');
        chatBubble.focus();
    }

    function toggleChat() {
        chatWindow.classList.contains('active') ? closeChat() : openChat();
    }

    chatBubble.setAttribute('aria-expanded', 'false');
    chatBubble.setAttribute('aria-controls', 'gano-chat-window');

    chatBubble.addEventListener('click', toggleChat);
    chatBubble.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); toggleChat(); }
    });

    // Focus trap — cicla entre inputField y sendBtn dentro del diálogo
    chatWindow.addEventListener('keydown', function (e) {
        if (!chatWindow.classList.contains('active')) return;

        if (e.key === 'Escape') {
            e.preventDefault();
            closeChat();
            return;
        }

        if (e.key !== 'Tab') return;

        const focusable = [inputField, sendBtn].filter(el => !el.disabled);
        if (focusable.length === 0) return;

        const first = focusable[0];
        const last  = focusable[focusable.length - 1];

        if (e.shiftKey) {
            if (document.activeElement === first) {
                e.preventDefault();
                last.focus();
            }
        } else {
            if (document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    });

    // ─── REFERENCIAS ─────────────────────────────────────────────────────────
    const sendBtn      = document.getElementById('gano-chat-send');
    const inputField   = document.getElementById('gano-chat-input');
    const msgContainer = document.getElementById('gano-chat-messages');

    let leadStep = 0;
    let userData = { name: '', whatsapp: '' };
    let nudged   = false;

    // ─── NUDGE PROACTIVO ─────────────────────────────────────────────────────
    // Activado a los 20 segundos si el chat no está abierto (patrón SOTA).
    // Mensaje empático, sin afirmaciones falsas sobre vulnerabilidades.
    setTimeout(function () {
        if (!chatWindow.classList.contains('active') && !nudged) {
            nudged = true;
            const notification = document.createElement('div');
            notification.className = 'gano-chat-nudge';
            notification.setAttribute('role', 'status');
            notification.style.cssText = [
                'position:fixed', 'bottom:100px', 'right:30px',
                'background:#1A1A2E', 'color:#fff', 'padding:12px 16px',
                'border-left:4px solid #D4AF37', 'border-radius:8px',
                'font-size:0.85rem', 'font-weight:600',
                'box-shadow:0 4px 20px rgba(0,0,0,0.35)',
                'z-index:10002', 'cursor:pointer', 'max-width:260px',
                'transition:opacity 0.3s ease'
            ].join(';');
            notification.innerHTML = '💬 ¿Tienes alguna duda sobre hosting o seguridad?<br><span style="color:#D4AF37;font-size:0.75rem;">El Agente Gano está disponible ahora.</span>';

            notification.addEventListener('click', function () {
                notification.remove();
                openChat();
            });

            document.body.appendChild(notification);
            setTimeout(function () {
                notification.style.opacity = '0';
                setTimeout(function () { notification.remove(); }, 300);
            }, 8000);
        }
    }, 20000);

    // ─── HELPERS ─────────────────────────────────────────────────────────────

    function addMessage(text, side) {
        const msg = document.createElement('div');
        msg.className = 'chat-msg ' + side;
        msg.textContent = text; // textContent en lugar de innerHTML — previene XSS
        msgContainer.appendChild(msg);
        msgContainer.scrollTop = msgContainer.scrollHeight;
    }

    /** Sanitizar input del usuario — eliminar caracteres de control */
    function sanitizeInput(text) {
        return text.replace(/[<>&"']/g, function (c) {
            return { '<': '', '>': '', '&': '&amp;', '"': '', "'": '' }[c];
        }).trim().substring(0, 500); // max 500 caracteres
    }

    /** Headers compartidos con nonce CSRF */
    function buildHeaders() {
        return {
            'Content-Type': 'application/json',
            'X-WP-Nonce':   cfg.nonce,
        };
    }

    /** Loguear evento en el servidor — con retry exponencial */
    async function postLog(message, level = 'INFO') {
        try {
            await fetchWithRetry(cfg.logEndpoint, {
                method:      'POST',
                credentials: 'same-origin',
                headers:     buildHeaders(),
                body:        JSON.stringify({ message, level }),
            });
        } catch (e) {
            // Silencioso — el log no es crítico para el usuario
        }
    }

    /** Fetch con reintentos y backoff exponencial */
    async function fetchWithRetry(url, options, retries = 3, backoff = 1000) {
        try {
            const response = await fetch(url, options);
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return await response.json();
        } catch (error) {
            if (retries > 0) {
                await new Promise(res => setTimeout(res, backoff));
                return fetchWithRetry(url, options, retries - 1, backoff * 2);
            }
            throw error;
        }
    }

    // ─── LÓGICA DE ENVÍO ─────────────────────────────────────────────────────

    async function handleSend() {
        const rawText = inputField.value;
        const text    = sanitizeInput(rawText);
        if (!text) return;

        addMessage(text, 'user');
        inputField.value = '';
        inputField.disabled = true; // Evitar doble envío

        try {
            // — Flujo de captura de lead —
            if (leadStep === 1) {
                userData.name = text;
                leadStep = 2;
                addMessage('Mucho gusto, ' + userData.name + '. ¿Me compartes tu WhatsApp para contactarte?', 'agent');
                return;
            }

            if (leadStep === 2) {
                userData.whatsapp = text;
                leadStep = 0;
                addMessage('¡Perfecto! En breve te contactamos. También puedes escribirnos a hola@gano.digital.', 'agent');
                await postLog('LEAD: ' + userData.name + ' | WA: ' + userData.whatsapp, 'LEAD');
                return;
            }

            // — Disparadores de captura de lead —
            const triggers = ['comprar', 'contratar', 'interesa', 'soporte', 'ayuda', 'asesoría', 'asesor', 'cotizar'];
            if (triggers.some(t => text.toLowerCase().includes(t)) && !userData.name) {
                leadStep = 1;
                addMessage('Con gusto te ayudo. Para darte atención personalizada, ¿cuál es tu nombre?', 'agent');
                return;
            }

            // — Respuesta del chat IA —
            const typing = document.createElement('div');
            typing.className = 'chat-msg agent';
            typing.textContent = 'Escribiendo...';
            msgContainer.appendChild(typing);

            const data = await fetchWithRetry(cfg.chatEndpoint, {
                method:      'POST',
                credentials: 'same-origin',
                headers:     buildHeaders(),
                body:        JSON.stringify({ message: text }),
            });

            typing.remove();
            addMessage(data.reply || 'No pude procesar tu solicitud. Intenta de nuevo.', 'agent');

        } catch (e) {
            addMessage('Problema de conexión. Intenta de nuevo en un momento.', 'agent');
        } finally {
            inputField.disabled = false;
            inputField.focus();
        }
    }

    sendBtn.addEventListener('click', handleSend);
    inputField.addEventListener('keypress', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSend();
        }
    });
});
