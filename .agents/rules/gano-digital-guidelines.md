# Gano Digital Project Guidelines

## Overview
Gano Digital is a WordPress hosting provider built on GoDaddy Reseller + Managed WordPress Deluxe. Commerce flows through GoDaddy Reseller Store (RCC) and Checkout. Phases 1-3 complete; Phase 4 (commercialization) in progress.

## Code Standards

### PHP/WordPress
- Follow WordPress Coding Standards (WPCS)
- Use MU plugins in `/wp-content/mu-plugins/` for core functionality
- Prefix all functions with `gano_` (e.g., `gano_security_nonce_check()`)
- Document security checks in comments (nonce, capability, sanitization)
- No hardcoded secrets; use environment variables (`.env`)

### JavaScript
- Modern ES6+; no IE11 support required
- Use strict mode
- Comment complex logic and business rules
- Namespace all global functions under `window.ganoApp`

### Configuration
- Never commit API keys, secrets, or credentials
- Use `.env` files (added to `.gitignore`)
- Document all constants with their purpose in `wp-config.php`

## Git Workflow

### Commits
- Format: `[PHASE] Type: Brief description`
- Example: `[Phase 4] Feat: Map PFID SKUs to RCC checkout flow`
- Include context in body (what, why, testing done)
- Sign commits with GPG when possible

### PRs
- Describe changes, why they matter, how to test
- Link to TASKS.md task item
- All tests must pass before merge
- Code review mandatory for Phase 4+ changes

## Commerce (Phase 4)

### GoDaddy Reseller Principles
- Primary checkout: GoDaddy Reseller Store (no local pasarela)
- Payment handling: 100% GoDaddy (no direct Stripe/Wompi)
- Customer management: RCC for domain/hosting orders
- Plugin modifications must align with Reseller, not override it

### PFID and SKU Mapping
- PFID = Product Family ID in GoDaddy RCC
- Map CTAs (`shop-premium.php`, pricing pages) → Reseller cart URLs
- Test in RCC sandbox before production
- Document all SKU→PFID mappings in `memory/commerce/rcc-pfid-checklist.md`

### Testing Checklist
- Reseller cart loads (no CORS errors)
- CTAs redirect correctly to checkout
- Order history visible in RCC
- CDN and SSL working after order creation

## Security (Phases 1-3 + ongoing)

### MU Plugin Compliance
- `gano-security.php`: CSP headers, CSRF nonces, rate limiting (429)
- `gano-seo.php`: Schema JSON-LD (no external dependencies)
- Both must load without fatal errors on every page

### Hardening Checklist
- WP_DEBUG off in production
- All secrets in `.env`, never in code
- Rate limiting active (REST API 429 after 60 req/min)
- nonce validation on all forms
- Wordfence monitoring enabled

## Obsidian + MCP Integration

### Vault Structure
- Task tracking in `04-TASKS/` (daily notes check for new tasks)
- Decisions logged in `00-PROJECTS/` (linked from commits)
- Phase progress in `05-DAILY-NOTES/` (weekly reviews)

### Agent Queries
- Claude: "Search vault for Phase 4 RCC decisions"
- Cursor: "@obsidian find PFID mappings in commerce notes"
- Antigravity: "Review Phase 4 checklist in Obsidian and report gaps"

## Phase 4 Focus Areas

### Priority 1 (This Sprint)
1. RCC catalog debug (PFID correctness, availability)
2. Reseller cart CTA mapping (`shop-premium.php` → checkout URLs)
3. Smoke test: Add to cart → Reseller checkout → order confirms in RCC

### Priority 2 (Next Sprint)
4. Staging site clones for pre-prod testing
5. Support workflow definition (FreeScout or similar)
6. Legal/Contact data audit (NIT, phone numbers in copy)

### Research Only (Reference, not priority while Reseller works)
- WHMCS integration (future billing if model changes)
- DIAN compliance (for future self-hosted billing)
- Developer API automation (optional layer above Reseller)

## Decision Log

### Active Decision: Reseller Store vs Developer API (2026-04-02)
- **Decision**: Commerce core = GoDaddy Reseller Store + RCC (not Developer API)
- **Why**: Faster to market, white-label checkout, no separate billing complexity
- **API Role**: Optional automation layer, NOT required for Phase 4 launch
- **Implication**: Don't block Phase 4 waiting for API integrations

### Active Decision: Staging Before Production (Managed WP)
- **Decision**: Use Managed WordPress Deluxe staging site for all pre-prod testing
- **Why**: Identical environment, CDN included, zero risk to live site
- **Implication**: All cart, plugin, SSL changes tested in staging first

## When in Doubt

1. Check `TASKS.md` for current Phase and priorities
2. Review `memory/projects/gano-digital.md` for full context
3. Search Obsidian for related decisions (vault accessible via MCP)
4. Ask Claude via dispatch queue if decision guidance needed
5. Document decision in Obsidian + link from commit

## Critical Guardrails

❌ **Never**
- Delete phase plugins without explicit Diego confirmation
- Commit API keys or credentials (even fake ones)
- Override Reseller Store logic without RCC testing
- Deploy Phase 4 changes directly to production (staging first)
- Assume API/billing/DIAN is required for checkout

✅ **Always**
- Test in staging/OTE first
- Document decisions in code and Obsidian
- Validate PFID mappings in RCC before live
- Check rate limiting and CSP headers on deployment
- Link commits to TASKS.md items
