#!/bin/bash

################################################################################
# Demo: Task 5 (Fix Homepage Blanca) — Claude & Kimi Team Workflow
# Shows live conversation, negotiation, and feedback between Claude and Kimi
################################################################################

# Source team client
source "./.gano-skills/kimi/kimi-team-client.sh"

PROJECT_ROOT="."

main() {
    echo ""
    echo_title "TASK 5: FIX HOMEPAGE BLANCA — TEAM APPROACH"
    echo_status "Goal: Resolve CSS specificity war between Royal Elementor & child theme"
    echo ""

    # ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    # PHASE 1: CLAUDE ANALYSIS & PROPOSAL
    echo ""
    echo_title "PHASE 1: CLAUDE ANALYZES THE PROBLEM"
    echo ""

    claude_propose "The homepage appears blank because Royal Elementor CSS has higher specificity
than our child theme CSS. Solution: Inject critical CSS in functions.php with
add_action('wp_enqueue_scripts', callback, 99) to load AFTER Elementor but
with sufficient specificity to override it."

    echo ""
    echo_claude "I propose:"
    echo "  1. Create wp-content/themes/gano-child/css/homepage-fix.css"
    echo "  2. Add high-specificity selectors (body.page-template-front .hero-dark)"
    echo "  3. Enqueue with priority 99 in functions.php"
    echo "  4. Verify hero text visible on production"
    echo ""

    # ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    # PHASE 2: KIMI REVIEW & FEEDBACK
    echo ""
    echo_title "PHASE 2: KIMI REVIEWS THE PROPOSAL"
    echo ""

    echo_kimi "Analyzing your approach..."
    sleep 1

    kimi_review "analyze-css-specificity-fix" "@{
        files = @('wp-content/themes/gano-child/functions.php', 'wp-content/themes/gano-child/style.css')
        task = 'Review CSS specificity conflict and validate proposed fix'
        context = 'Royal Elementor addons vs child theme hierarchy'
    }"

    sleep 2

    echo ""
    echo_kimi "FEEDBACK:"
    echo_kimi "✓ Approach is sound — priority 99 will load after Elementor"
    echo_kimi "! Alternative: Use !important sparingly on 2-3 critical rules (faster than new file)"
    echo_kimi "! Check: Does homepage use a custom post template or is it front-page.php?"
    echo_kimi "⚠ Risk: If you use body.page-template-front but homepage is template-home, selectors won't match"
    echo ""

    # ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    # PHASE 3: NEGOTIATION
    echo ""
    echo_title "PHASE 3: TEAM NEGOTIATION"
    echo ""

    negotiate "Implementation Strategy" \
        "Create new CSS file (clean, maintainable)" \
        "Add inline !important rules (faster, but less maintainable)"

    echo_negotiation "Decision: Hybrid approach"
    echo_negotiation "- Use !important on 3 critical rules (hero dark bg, h1 color, CTA visibility)"
    echo_negotiation "- Keep in functions.php enqueue (no new file bloat)"
    echo_negotiation "- Document why !important was necessary"
    echo ""

    echo_negotiation "Also: Verify page template before deployment"
    echo_negotiation "- Check is_front_page() or is_home() in functions.php"
    echo_negotiation "- Test selectors in browser DevTools first"
    echo ""

    # ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    # PHASE 4: IMPLEMENTATION
    echo ""
    echo_title "PHASE 4: CLAUDE IMPLEMENTS (AGREED APPROACH)"
    echo ""

    echo_claude "Implementing agreed approach..."
    echo_claude "- Critical rules with !important for homepage hero"
    echo_claude "- Conditional enqueue via is_front_page()"
    echo_claude "- In-code documentation"
    echo ""

    # Simulated implementation
    sleep 2
    echo_success "Implementation complete (see functions.php changes)"

    # ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    # PHASE 5: KIMI VALIDATES
    echo ""
    echo_title "PHASE 5: KIMI VALIDATES IMPLEMENTATION"
    echo ""

    echo_kimi "Running validation checks..."

    kimi_review "validate-homepage-fix" "@{
        files = @('wp-content/themes/gano-child/functions.php', 'wp-content/themes/gano-child/style.css')
        task = 'Validate CSS fix specificity and page template logic'
        checks = @('syntax', 'specificity', 'performance', 'accessibility')
    }"

    sleep 2

    echo ""
    echo_kimi "VALIDATION RESULTS:"
    echo_kimi "✓ Specificity: body.page-home .gano-trust-hero h1 beats Royal Elementor"
    echo_kimi "✓ Syntax: No PHP/CSS errors detected"
    echo_kimi "✓ Performance: Inline CSS + !important (3 rules) = negligible impact"
    echo_kimi "✓ Accessibility: Focus states preserved, contrast maintained"
    echo_kimi "⚠ Note: Tested on /ecosistemas/ — works! Confirmed for front-page.php"
    echo ""

    # ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    # PHASE 6: FINAL APPROVAL
    echo ""
    echo_title "PHASE 6: TEAM APPROVAL & COMMIT"
    echo ""

    echo_claude "All checks passed. Committing changes..."
    echo ""

    echo_success "Task 5 Implementation: READY FOR MERGE"
    echo_success "- Homepage now displays correctly"
    echo_success "- CSS specificity conflict resolved"
    echo_success "- Validated by both Claude and Kimi"
    echo ""

    # ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    # SUMMARY & TRANSCRIPT
    echo ""
    echo_title "TEAM COLLABORATION SUMMARY"
    echo ""

    echo "📊 Session Metrics:"
    echo "  - Claude Proposals: 1 (CSS fix approach)"
    echo "  - Kimi Reviews: 2 (initial + validation)"
    echo "  - Negotiations: 1 (hybrid implementation strategy)"
    echo "  - Final Status: ✅ APPROVED"
    echo ""

    transcribe_session "Task5_HomepageFix"

    echo ""
    echo_success "Demo Complete! See transcript for full conversation."
    echo ""
}

# Run main
main "$@"
