# Skill: Phase 4 Commerce Implementation

## Metadata
- **Name**: phase4-commerce
- **Description**: Complete Phase 4 commercialization for Gano Digital: GoDaddy Reseller Store integration, PFID mapping, checkout flow verification, and RCC testing. Use when implementing or debugging Reseller features, mapping products to checkout, or testing Phase 4 workflows.
- **Scope**: Workspace (`gano-digital/`)
- **Dependencies**: gano-reseller-enhancements plugin, GoDaddy Reseller access, Obsidian vault

## Activation Signals
Automatically loaded when prompt contains:
- "Phase 4", "Reseller", "RCC", "PFID", "checkout", "cart", "shop-premium", "commercialize"

## Knowledge Base

### GoDaddy Reseller Fundamentals
1. **Reseller Store** = White-label shopping cart (customer-facing)
2. **RCC** = Reseller Control Center (backend order management)
3. **PFID** = Product Family ID (maps WordPress plans → GoDaddy products)
4. **3-year bundle** = Discounted multi-year plan (must be handled as single SKU in cart)
5. **OTE** = Official Test Environment (sandbox before production)

### Current Phase 4 State
- **Completed**: Architecture decision (Reseller vs API), security hardening (Phase 2)
- **In Progress**: PFID mapping, CTA wiring, staging tests
- **Blocked By**: None currently
- **Next**: Full cart/checkout test in staging, then production migration

### Critical Files
| File | Purpose | Last Updated |
|------|---------|--------------|
| `memory/commerce/rcc-pfid-checklist.md` | PFID mapping tracker | Phase 4 start |
| `wp-content/plugins/gano-reseller-enhancements/` | Price overrides, bundle logic | 2026-03 |
| `wp-content/themes/gano-child/templates/shop-premium.php` | CTA template | 2026-03 |
| `wp-content/mu-plugins/gano-security.php` | CSP headers, rate limiting | Phase 2 |
| `TASKS.md` | Sprint-level tasks | Daily |

### Reseller Integration Pattern

#### CTA Structure
```php
<!-- shop-premium.php -->
<a href="<?php echo esc_url(gano_reseller_cart_url('PFID_3YEAR_WP')); ?>" class="btn btn-primary">
    Get Started
</a>
```

#### Plugin Function (gano-reseller-enhancements)
```php
function gano_reseller_cart_url($pfid) {
    $base = defined('GANO_RESELLER_BASE') ? GANO_RESELLER_BASE : 'https://reseller.godaddy.com/cart';
    $shopper_id = get_option('gano_reseller_shopper_id');

    return add_query_arg([
        'plid' => $pfid,
        'domreg' => 1,
        'psid' => $shopper_id
    ], $base);
}
```

#### PFID Mapping Example
```
WordPress Hosting - Annual → PFID: 12345 (gano_reseller_cart_url('12345'))
WordPress Hosting - 3 Year → PFID: 12346 (gano_reseller_cart_url('12346'))
Dedicated Hosting - Annual → PFID: 54321 (gano_reseller_cart_url('54321'))
```

### Testing Sequence (Staging First)

1. **Cart Load Test**: Verify CTA → Reseller cart without CORS errors
2. **Product Display**: Verify product name, price, renewal date shown correctly
3. **Add-to-cart**: Verify 3-year plan options available and selectable
4. **Checkout Flow**: Proceed from cart → checkout without timeout
5. **Order Creation**: Verify order ID generated, appears in RCC within 5 min
6. **Email Confirmation**: GoDaddy email received with order details
7. **Repeat in Production**: Only after staging 100% pass

### Security Checklist
- [ ] CSP headers allow godaddy.com domain
- [ ] Rate limiting (429) active for Reseller API calls
- [ ] No sensitive data in cart URLs (use query params safely)
- [ ] HTTPS enforced on all Reseller redirects
- [ ] Order handling: zero custom order storage (RCC is source of truth)

### Common Issues & Fixes

| Issue | Root Cause | Fix |
|-------|-----------|-----|
| Cart shows 404 | Wrong PFID or Reseller URL | Check `gano_reseller_cart_url()` function, verify PFID in RCC |
| CORS Error | CSP header blocks godaddy.com | Add `connect-src: godaddy.com` to `gano-security.php` |
| Checkout timeout | Network latency or Reseller API slow | Add retry logic, increase timeout to 30s, check OTE first |
| Order not in RCC | Reseller webhooks not registered | Verify webhook URL in Reseller API config, test webhook |
| 3-year pricing wrong | Bundle handler not active | Verify `gano-reseller-enhancements` bundle logic, check ACF overrides |

## Step-by-Step Workflow

### When Mapping a New Product
1. Obtain PFID from RCC (log into RCC → Manage Products → find product → note PFID)
2. Add to PFID checklist: `productName → PFID-12345 → [test date]`
3. Update `gano-reseller-enhancements` with new mapping (or ACF field if using price override)
4. Create CTA in theme template using `gano_reseller_cart_url('12345')`
5. Test in staging: Navigate CTA → Reseller cart → verify product shown
6. Document in commit: "Map [Product] to PFID-12345, test result in staging"
7. Once merged: Test again in production
8. Update checklist: Mark as "Live ✅ YYYY-MM-DD"

### When Debugging Cart Error
1. Check browser console: Are there JavaScript errors?
2. Check network tab: Is Reseller cart URL correct? Does it 404?
3. Check CSP headers: `curl -I gano.digital` — does it include `connect-src`?
4. Check PFID: Is the PFID valid in RCC?
5. Fallback: Open RCC directly, try adding product manually
6. Log in `wp-content/agent-debug.log`: Include timestamp, error, PFID, URL
7. Escalate: Post in Obsidian with screenshot + logs

### When Testing Checkout Flow
1. Use `/reseller-cart-test staging` workflow for full automation
2. Or manual test: Visit staging.gano.digital → click CTA → proceed through checkout
3. After order: Wait 5 minutes, check RCC for order
4. Verify email: Check test@gano.digital inbox
5. Screenshot all steps, save to `memory/commerce/screenshots/`
6. Update TASKS.md with test result + date

## Rules Applied to This Skill

When using this skill, Antigravity follows:
- **gano-digital-guidelines**: Code standards, git workflow, security
- **phase4-commerce-rules**: PFID validation, CTA verification, staging-first, rate limiting

## Commands for Antigravity

```
Use `/phase4-rcc-audit` to audit current RCC configuration and identify gaps
Use `/reseller-cart-test staging` to run full end-to-end test in staging
Use `/reseller-cart-test production` to verify production readiness
Ask "@obsidian find PFID mappings" to search vault for PFID reference
Ask "Review Phase 4 checklist and suggest next task" for AI guidance
```

## Success Criteria for Phase 4 Completion

When this skill is no longer needed, all of the following must be true:

- ✅ PFID checklist 100% populated (all 5+ product families mapped)
- ✅ All CTAs correctly wired to Reseller cart
- ✅ Staging `/reseller-cart-test staging` passes 9/9 steps
- ✅ Production `/reseller-cart-test production` passes 9/9 steps
- ✅ Zero CORS errors, zero timeout, zero 500 errors
- ✅ Order confirmation visible to customer in < 5 sec
- ✅ Order appears in RCC within 5 min of checkout
- ✅ GoDaddy email confirmation received within 5 min
- ✅ gano-reseller-enhancements plugin stable (no regressions)
- ✅ Security: CSP headers, rate limiting, nonce validation all active

## Reference Documents (Read These First)

1. `TASKS.md` — Current Phase 4 sprint items
2. `CLAUDE.md` — Project context, stack, decision log
3. `memory/commerce/rcc-pfid-checklist.md` — PFID tracker (live document)
4. `memory/research/sota-apis-mercadolibre-godaddy-2026-04.md` §3.7 — Decision: Reseller vs API
5. `memory/projects/gano-digital.md` — Full Phase 4 plan

---
**Skill Status**: ACTIVE (Phase 4 in progress)
**Last Updated**: 2026-04-06
**Next Review**: After Phase 4 launch (2026-04-20 estimated)
