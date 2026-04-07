# Phase 4 Commerce Implementation Rules

## Automation Rules for RCC + Reseller Store

### Rule 1: PFID Validation
- Before mapping any product to Reseller cart, verify PFID exists in RCC
- Document mapping in `memory/commerce/rcc-pfid-checklist.md`
- Test cart URL in staging Reseller sandbox
- Report: productName → PFID → cartURL

### Rule 2: CTA-to-Checkout Flow
- All "Buy Now", "Get Started", pricing page CTAs must point to Reseller cart
- Use `shop-premium.php` as template for consistent styling
- Test that Reseller cart loads without CORS errors
- Verify add-to-cart preserves product options (e.g., 3-year plan selection)

### Rule 3: Reseller Plugin Harmony
- `gano-reseller-enhancements` plugin is the ONLY place for Reseller customization
- Never modify core Reseller Store plugin files
- ACF fields for price override must reference PFID consistently
- Bundle handler for 3-year SKUs must apply discount automatically

### Rule 4: Order Confirmation Flow
- After Reseller checkout completes, customer lands on order confirmation page
- Confirmation must show: order ID, domain/hosting plan, renewal date, next steps
- Email confirmation from GoDaddy (not our system) serves as receipt
- No manual order creation in our database

### Rule 5: Staging-First Deployment
- All Phase 4 changes must be tested in staging.gano.digital first
- Staging must mirror production: same plugins, same theme, same Reseller config
- Test scenarios:
  1. Browse products in Reseller catalog
  2. Add Wordpress plan to cart (verify 3-year option)
  3. Proceed to Reseller checkout
  4. Verify order appears in RCC within 5 minutes
  5. Verify customer receives GoDaddy confirmation email
- Only after staging pass: promote to gano.digital production

### Rule 6: Data Integrity
- No orders created outside RCC (all through Reseller)
- Customer data lives in GoDaddy RCC, not our database
- Log all API calls to RCC in `wp-content/agent-debug.log` for debugging
- Backup RCC settings before any configuration change

### Rule 7: Communication in Spanish
- All error messages to customers in Spanish (GoDaddy UI is en, our wrapper is es)
- Documentation and comments can be English
- CTAs and pricing pages: Spanish primary, English secondary

### Rule 8: Rate Limiting + Security
- All Reseller API calls must respect 60 req/min rate limit
- Add X-Request-ID headers to all API calls for tracing
- Log failed calls with timestamp + error code + recovery action
- Never retry failed Reseller calls >3 times (contact support)

### Rule 9: Version Control for Reseller Config
- Export RCC settings to JSON file after changes: `memory/commerce/rcc-backup-YYYY-MM-DD.json`
- Store in git (with sensitive IDs masked)
- Makes rollback possible if misconfiguration occurs

### Rule 10: Documentation Trail
- Every PFID mapping change → entry in `rcc-pfid-checklist.md`
- Every CTA modification → screenshot before/after in `memory/commerce/screenshots/`
- Every Reseller cart test → result logged in daily notes or task item
- Makes it possible to trace "why this PFID" 3 months later

## Automation Instruction for Antigravity

When tasked with Phase 4 work:
1. Read `memory/commerce/rcc-pfid-checklist.md` to understand current state
2. Read `TASKS.md` Phase 4 section to understand exact requirement
3. Check `gano-reseller-enhancements` plugin for existing patterns
4. Make change in staging first; document in memory/commerce/
5. Test in staging Reseller sandbox (use test credit card if needed)
6. Generate screenshot or browser recording showing test result
7. Update PFID checklist with new mapping + test result date
8. Propose PR with before/after diffs + test evidence

## Security Guardrails for Antigravity

❌ **Terminal Commands Antigravity Must NOT Execute**
- `rm -rf` anything without explicit Diego approval
- `git push --force` (always force-push protection)
- Any command with GoDaddy API keys (should be in .env, never passed to CLI)
- Database direct queries (use WP CLI only: `wp db query`)

✅ **Terminal Commands Antigravity Can Auto-Execute**
- `git status`, `git diff`, `git log` (read-only)
- `wp plugin list`, `wp user list` (WP-CLI read-only)
- `npm test`, `npm run build` (build automation)
- `curl` to Reseller OTE API (test endpoint only)

## Success Criteria for Phase 4 Completion

- [ ] All 5 PFID families mapped and tested in staging RCC
- [ ] `shop-premium.php` CTA redirects to Reseller cart without errors
- [ ] 3-year bundle pricing applies automatically in cart
- [ ] Order confirmations appear in RCC within 5 minutes of checkout
- [ ] Customer receives GoDaddy email confirmation
- [ ] Staging and production both pass smoke test above
- [ ] `memory/commerce/rcc-pfid-checklist.md` 100% populated
- [ ] Zero security violations (rate limit, nonce, CSP)
- [ ] PR merged with full test evidence (screenshots/recordings)
