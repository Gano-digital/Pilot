# Cursor Active Context — Gano Digital 2026-04-06

**Synced from:** `CLAUDE.md` (main project memory)  
**Last sync:** 2026-04-06  
**Status:** SOTA investigation complete; multi-agent alignment in progress

---

## Proyecto Actual
- **Sitio:** gano.digital (hosting WordPress Colombia)
- **Stack:** WP + Elementor + WooCommerce (checkout via **GoDaddy Reseller**)
- **Fase:** 4 (comercialización dentro de GoDaddy)
- **Plan Maestro:** Auditoría integral + roadmap 5 fases (Marzo 2026)

---

## Que Hemos Completado ✅
**Fases 1–3:** Parches críticos, hardening, SEO/Performance  
**MCP Integration:** .cursor/mcp.json poblado (GitHub + Filesystem)  
**Debugging:** .vscode/launch.json con XDebug + Node configs  
**Configuration:** .cursorrules con patrones PFID + seguridad WordPress  
**Dispatch Queue:** .claude/dispatch-unified.json con roadmap 4 semanas  

---

## Próximos Pasos (HIGH PRIORITY)

### W1 (0–3h cada uno)
1. **Cursor MCP Verify** — test GitHub + Filesystem MCPs con queries reales
2. **VS Code Launcher Verify** — test XDebug en port 9003; Node debugger en .gsd
3. **Cursor Rules Rollout** — v0.45+ required; test code gen con PFID patterns
4. **Antigravity Clarification** — BLOCKING: Diego define rol + integración

### W2 (1–2h cada uno)
5. **Dispatch Prompt Enhance** — PFID constants, MCP signals, error patterns
6. **Workspace Profile** — gano.code-workspace dual context (WP + .gsd)
7. **SDK Upgrade** — @anthropic-ai/claude-agent-sdk v0.2.84 → v0.2.92

### W3–4
8. **gano-cursor-models refresh** — skill outdated (Feb 2026)
9. **Quarterly SOTA automation** — checklist script + quarterly workflow

---

## Términos Clave (Quick Reference)

| Término | Significado |
|---------|-------------|
| **Reseller** | GoDaddy Reseller Store + RCC (checkout marca blanca) |
| **PFID** | Product Family ID (constantes para catálogo Reseller) |
| **MCP** | Model Context Protocol (GitHub, Filesystem integration) |
| **nonce CSRF** | wp_verify_nonce() + form hidden fields (seguridad WP) |
| **sanitize** | sanitize_text_field() / sanitize_url() (WP security) |
| **rate limit** | set_transient() por IP (prevenir abuse REST) |

---

## Stack Tecnológico (Resumen)

```
WordPress + Elementor + WooCommerce
├── Seguridad: Wordfence + MU plugins (gano-security.php, gano-seo.php)
├── Checkout: GoDaddy Reseller Store (NO pasarelas locales)
├── API: GoDaddy Developer Portal (opcional; no reemplaza Reseller)
├── Tema: Hello Elementor → gano-child (functions.php + templates)
├── SEO: Rank Math + schema JSON-LD (Org, LocalBiz, Product, FAQ)
├── UX: Chat IA (gano-chat.js) + Quiz Soberanía Digital
└── Hosting: Managed WordPress Deluxe (20GB NVMe, CDN, staging)
```

---

## Cursor-Specific Patterns (from .cursorrules)

### PHP Form Handling (WordPress)
```php
// 1. Verify nonce
if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'gano_form_action' ) ) {
    wp_die( 'Security check failed' );
}

// 2. Sanitize input
$email = sanitize_email( $_POST['email'] );
$text = sanitize_text_field( $_POST['name'] );

// 3. Escape output
echo esc_html( $user_name );
echo esc_url( $redirect_url );

// 4. Rate limit (via transient)
$key = 'gano_api_' . $_SERVER['REMOTE_ADDR'];
if ( get_transient( $key ) ) {
    wp_send_json_error( [ 'message' => 'Rate limited' ], 429 );
}
set_transient( $key, 1, 60 ); // 60 sec limit
```

### Reseller Cart Helper
```php
function gano_rstore_cart_url( $pfid, $extra_params = [] ) {
    $base = 'https://reseller.godaddy.com/cart';
    $params = array_merge( [ 'pfid' => $pfid ], $extra_params );
    return add_query_arg( $params, $base );
}
// Usage: gano_rstore_cart_url( 4, [ 'qty' => 1 ] )
```

### JavaScript DOM Manipulation (Vanilla)
```javascript
// Always prefer vanilla JS in Gano; avoid jQuery
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.querySelector('[data-action="buy"]');
    btn?.addEventListener('click', (e) => {
        e.preventDefault();
        const pfid = btn.dataset.pfid;
        window.location.href = `/checkout?pfid=${pfid}`;
    });
});
```

---

## Common Mistakes to Avoid

❌ **DON'T:** Hardcode API secrets in code  
✅ **DO:** Use wp_options + wp_salt() or env vars

❌ **DON'T:** Trust user input  
✅ **DO:** Always sanitize + escape

❌ **DON'T:** Use local pasarela (Wompi, etc.) for checkout  
✅ **DO:** Route to GoDaddy Reseller Store

❌ **DON'T:** Modify phase plugins (`gano-phase-*`) without Diego approval  
✅ **DO:** Only extend via child theme or custom plugins

❌ **DON'T:** Forget nonce in forms  
✅ **DO:** Every form = wp_nonce_field() + wp_verify_nonce()

---

## Quick Checklist (Before Commit)

- [ ] No hardcoded secrets in code
- [ ] All forms include wp_nonce_field()
- [ ] User input sanitized (sanitize_text_field, etc.)
- [ ] Output escaped (esc_html, esc_url, etc.)
- [ ] API calls use wp_remote_post() with timeout + error handling
- [ ] Reseller CTAs point to gano_rstore_cart_url() helper
- [ ] CSS uses token-based design system (no magic numbers)
- [ ] JavaScript vanilla DOM (no jQuery for new code)
- [ ] Rate limiting on REST endpoints (set_transient)
- [ ] CSP headers set in gano-security.php (no inline scripts)

---

## Resources (External)

| Recurso | URL |
|---------|-----|
| WordPress Nonce | https://developer.wordpress.org/plugins/security/nonces/ |
| GoDaddy Reseller | https://www.godaddy.com/es/programa-reseller |
| GoDaddy API Docs | https://developer.godaddy.com/doc |
| Intelephense (PHP 8.2) | https://intelephense.com/ |

---

## Notas Críticas

1. **GoDaddy Reseller es canónico** — No API, no WHMCS, no billing custom
2. **Phase plugins NO eliminar** — Hasta activarlos en WP
3. **BLOCKING:** Antigravity status TBD — Awaiting Diego input
4. **MCP v0.45+** — Cursor debe actualizarse para activar GitHub MCP
5. **XDebug puerto 9003** — Asegurar que no conflicte con otros servicios

---

*Auto-synced from CLAUDE.md. Actualizar si cambia el contexto del proyecto.*
