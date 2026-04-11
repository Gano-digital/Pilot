# Workflow: Phase 4 RCC Audit

## Metadata
- **Name**: phase4-rcc-audit
- **Description**: Comprehensive audit of GoDaddy RCC configuration, PFID mappings, and Reseller cart readiness for Phase 4 launch
- **Trigger**: `/phase4-rcc-audit`
- **Time Est**: 30-45 minutes
- **Outputs**: Audit report with gaps + recommendations

## Steps

### 1. Current State Assessment
- [ ] Read `memory/commerce/rcc-pfid-checklist.md` to understand existing mappings
- [ ] Extract all PFID → SKU mappings from file
- [ ] Count: How many are mapped vs pending?
- [ ] Check dates: When was last mapping added? (indicates progress velocity)

### 2. CTA Verification
- [ ] Scan `wp-content/themes/gano-child/templates/` for CTA files
- [ ] List all "Buy Now" buttons and their current target URLs
- [ ] For each CTA: Does it point to Reseller cart? Is the cart URL correctly formatted?
- [ ] Check `shop-premium.php` template for Reseller cart URL pattern
- [ ] Report: How many CTAs are correctly pointing to Reseller vs broken/internal?

### 3. Plugin Alignment
- [ ] Check `wp-content/plugins/gano-reseller-enhancements/` exists and is enabled
- [ ] Read plugin code: What price overrides are active?
- [ ] Verify: Does bundle handler for 3-year SKUs exist?
- [ ] Check for any hardcoded product IDs or PFID references
- [ ] Report: Is plugin fully aligned with current RCC configuration?

### 4. Staging vs Production Parity
- [ ] Compare staging.gano.digital Reseller config with production
- [ ] Plugins: Are they identical versions and enabled status?
- [ ] Theme files: Are staging CTAs pointing to staging Reseller cart or production?
- [ ] PFID mappings: Are they the same in both environments?
- [ ] Report discrepancies (staging should have OTE/sandbox URLs, prod should have live)

### 5. Security & Rate Limiting
- [ ] Check `gano-security.php` MU plugin for rate limiting rules
- [ ] Verify: Is REST API limited to 60 req/min?
- [ ] Check CSP headers for Reseller iframe/redirect URLs (need to allow godaddy.com)
- [ ] Scan logs (`wp-content/agent-debug.log`) for any Reseller API errors
- [ ] Report: Are there any security gaps for Phase 4 launch?

### 6. Missing Data
- [ ] Check `CLAUDE.md` "Preferencias de Diego" section: Are legal/contact details provided?
- [ ] Scan theme files for `{NIT}`, `{PHONE}`, placeholder text
- [ ] List all placeholder fields that need real data
- [ ] Report: What data must Diego provide before launch?

### 7. Recommendations
- [ ] Prioritize gaps by impact: Critical → High → Medium → Low
- [ ] For each gap, suggest exact file/function to modify
- [ ] Estimate effort (< 1hr, 1-2hr, 2-4hr, 4+hr)
- [ ] Recommend testing sequence (staging smoke tests before prod)

## Output Format

Generate a markdown report:
```markdown
# Phase 4 RCC Audit Report — YYYY-MM-DD

## Executive Summary
- Overall readiness: X% (Fully Ready / Ready with Notes / Needs Work)
- Critical gaps: N
- High priority items: N
- Estimated time to launch: X hours

## Current State
- PFIDs mapped: X/5 families
- CTAs verified: X/Y buttons
- Plugin alignment: ✅/❌
- Staging parity: ✅/❌

## Gaps (Prioritized)

### Critical 🔴
1. [Gap name]
   - Impact: [why it blocks launch]
   - File: [where to fix]
   - Effort: [time estimate]
   - Action: [exact change needed]

### High 🟡
[...similar format]

### Medium 🟠
[...similar format]

## Testing Checklist
- [ ] Staging smoke test passed
- [ ] Reseller cart loads without CORS errors
- [ ] Add-to-cart preserves product options
- [ ] Order confirmation visible in RCC
- [ ] Customer receives GoDaddy email

## Approval Checklist
- [ ] Diego approves all legal/contact data
- [ ] All critical + high gaps resolved
- [ ] Staging tests 100% pass
- [ ] Production ready for Phase 4 launch

---
Next step: Run `/reseller-cart-test` for end-to-end verification
```

## Success Criteria
- ✅ Report identifies all gaps
- ✅ Recommendations are actionable (specific files, effort estimates)
- ✅ Readiness percentage clearly stated
- ✅ Suggests next workflow if gaps remain
