# Workflow: Reseller Cart End-to-End Test

## Metadata
- **Name**: reseller-cart-test
- **Description**: Complete end-to-end smoke test of GoDaddy Reseller Store cart, checkout, and order confirmation (staging or production)
- **Trigger**: `/reseller-cart-test [staging|production]`
- **Time Est**: 15-20 minutes
- **Outputs**: Video recording + test report + Pass/Fail for each step

## Prerequisites
- Antigravity browser agent must be configured with Chrome extension installed
- User must provide environment (staging or production)
- For staging: GoDaddy OTE/sandbox credentials may be needed

## Test Scenario

### Step 1: Navigate to Product Page
- Browser: Open gano.digital homepage (or staging.gano.digital)
- Action: Locate "Get Started" or "Buy Now" CTA
- Verify: Button is visible and clickable
- Screenshot: Before clicking

### Step 2: Click CTA → Reseller Cart
- Browser: Click CTA button
- Wait: Cart page loads (monitor for CORS errors, timeouts)
- Verify: URL is Reseller cart domain (reseller.godaddy.com or staging equivalent)
- Verify: No JavaScript errors in console
- Screenshot: Cart page loaded

### Step 3: Verify Cart Contents
- Browser: Check product displayed (e.g., "WordPress Hosting - 3 Year")
- Verify: Pricing is correct (should match shop-premium.php)
- Verify: 3-year discount applied automatically (if applicable)
- Verify: Renewal date is accurate
- Screenshot: Cart with product details

### Step 4: Proceed to Checkout
- Browser: Click "Proceed to Checkout" or "Buy Now" button in cart
- Wait: Checkout page loads
- Verify: No CORS errors, no blank pages
- Verify: Customer data form is visible (if first-time customer)
- Screenshot: Checkout page

### Step 5: Fill Checkout Form (Staging Only)
- Browser: Fill form with test data:
  - Name: "Test Customer"
  - Email: "test@gano.digital"
  - Address: "Bogotá, Colombia"
  - Domain: "test-domain-YYYYMMDD-HH.co" (auto-generate unique)
- Verify: Form validates without errors
- Screenshot: Completed form

### Step 6: Payment (Staging Only)
- Browser: Proceed to payment section
- Use GoDaddy test credit card: 4111-1111-1111-1111 (Visa test card)
- Exp: Any future date, CVC: Any 3 digits
- Verify: Payment form is secure (HTTPS, CSP headers correct)
- Screenshot: Payment form ready

### Step 7: Confirm Order
- Browser: Submit payment
- Wait: Order confirmation page loads (typically "Thank you for your order")
- Verify: Order ID is displayed
- Verify: Product details match cart
- Verify: Next steps / support contact info visible
- Screenshot: Order confirmation page
- Video: Browser recording of entire checkout flow

### Step 8: Verify Order in RCC
- Action: Check GoDaddy RCC (Reseller Control Center)
- Query: Search for order ID from Step 7
- Verify: Order appears in RCC with correct status
- Verify: Customer email matches
- Verify: Product (domain/hosting) provisioned or pending (acceptable for test)
- Screenshot: Order in RCC

### Step 9: Verify Customer Email
- Action: Check test email inbox (test@gano.digital or equivalent)
- Verify: GoDaddy order confirmation email received within 5 minutes
- Verify: Email contains order ID, product details, login credentials
- Verify: Email is professional and in correct language (Spanish if applicable)
- Screenshot: Email received

### Step 10: Summary Report
- Count: Total steps passed (0-10)
- Time: Actual time taken (target: < 15 min)
- Errors: List any CORS errors, timeouts, form validation issues
- Recommendations: What to fix if any step failed

## Output Format

Generate markdown report:
```markdown
# Reseller Cart Test Report — YYYY-MM-DD [STAGING|PRODUCTION]

## Test Environment
- **Site**: gano.digital / staging.gano.digital
- **Tester**: Antigravity Agent
- **Date & Time**: YYYY-MM-DD HH:MM UTC

## Results Summary
| Step | Description | Status | Notes |
|------|-------------|--------|-------|
| 1 | Navigate to product page | ✅/❌ | [Any issues] |
| 2 | Click CTA → Reseller cart | ✅/❌ | [Any CORS errors] |
| 3 | Verify cart contents | ✅/❌ | [Pricing correct?] |
| 4 | Proceed to checkout | ✅/❌ | [Page load time?] |
| 5 | Fill checkout form | ✅/❌ | [Validation errors?] |
| 6 | Enter payment | ✅/❌ | [Security headers OK?] |
| 7 | Confirm order | ✅/❌ | [Confirmation visible?] |
| 8 | Verify in RCC | ✅/❌ | [Order ID found?] |
| 9 | Verify email receipt | ✅/❌ | [Email received?] |

## Overall Result
- **Pass/Fail**: ✅ PASS / ❌ FAIL (X/9 steps passed)
- **Ready for launch**: YES / NO / CONDITIONAL

## Issues Found
[If any step failed, list here with details and impact]

## Evidence
- Browser recording: [video embedded or link]
- Screenshots: [gallery of all screenshots]
- Console errors: [any JS errors captured]
- Network issues: [any CORS/timeout errors]

## Recommendations
[What to fix before next test, if needed]

---
Next step: Deploy to [opposite environment] and re-run test
```

## Pass Criteria (All Must Be True)
- ✅ All 9 steps completed without critical errors
- ✅ Cart loads from correct Reseller URL
- ✅ Pricing matches site copy
- ✅ Order confirmation visible
- ✅ Order appears in RCC within 5 minutes
- ✅ Customer email received within 5 minutes
- ✅ Zero CORS errors, zero blank pages, zero 500 errors
- ✅ Full browser recording captured

## Fail Criteria (Any One = FAIL)
- ❌ Cart page shows 404 or redirects to wrong URL
- ❌ Checkout timeout (> 30 sec)
- ❌ Payment declined (unless using staging card)
- ❌ Order not visible in RCC after 10 min
- ❌ Customer email not received after 10 min
- ❌ Any JavaScript console error that blocks interaction
- ❌ CSP header blocks Reseller iframe/redirect

## Troubleshooting

If test fails, check:
1. **CORS Error** → Verify CSP headers allow godaddy.com
2. **Cart URL wrong** → Check CTA in shop-premium.php points to correct Reseller URL
3. **Pricing mismatch** → Verify gano-reseller-enhancements plugin price overrides
4. **Order not in RCC** → Check Reseller API integration, rate limiting
5. **Email not received** → Check GoDaddy SMTP settings, spam folder

---
**Critical**: Always run test in staging first before production. If test fails in staging, DO NOT proceed to production.
