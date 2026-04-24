---
title: Claude Design System Integration — Gano Digital
date: 2026-04-24
version: 1.0
status: Approved for Implementation
---

# Claude Design System Integration — gano.digital

## Executive Summary

Integrate the official Claude Design System (exported from Claude.ai/design) into gano.digital WordPress theme. This spec defines the complete architecture for tokenizing, componentizing, and deploying a unified design language across marketing (light) and premium SOTA (dark) surfaces.

**Scope:** Full component library (buttons, cards, keycaps, badges, panels, pricing tiers, catalog cards) + design tokens + conditional light/dark theming + logo system.

**Deployment:** GitHub workflow → PR → merge to main → webhook auto-deploys via rsync.

**Timeline:** Single large PR, all-in-one deployment, manual testing on production.

---

## 1. Architecture Overview

### 1.1 Design System Foundation

The Claude Design System provides:
- **Design tokens** (`colors_and_type.css`): Colors, typography (Plus Jakarta Sans + Inter + JetBrains Mono), spacing, radii, motion
- **Two design surfaces:**
  - **Light (Marketing)**: Blue/Green/Orange, used for homepage, /ecosistemas/, landing pages
  - **Dark (SOTA Hub)**: Gold/Purple/Indigo on `#0F1115` dark monolith, used for /shop/, /catalog/, premium content
- **Component previews**: Reference HTML/CSS for buttons, pricing cards, catalog cards, keycaps, badges, monolith panels
- **UI kit layouts**: Marketing homepage reference + SOTA shop reference

### 1.2 Integration Model

**Approach B: Full Component Library**

```
Design Assets (extracted from archive)
    ↓
gano-child/ CSS files (design tokens + component styles)
    ↓
gano-child/ PHP components (reusable functions)
    ↓
Feature branch → PR → code review → merge to main
    ↓
GitHub webhook triggers → rsync to server
    ↓
Manual testing on production (light/dark modes, all pages)
```

### 1.3 File Structure

```
wp-content/themes/gano-child/
├── css/
│   ├── design-tokens.css              [← from colors_and_type.css]
│   ├── components-buttons.css
│   ├── components-cards.css           [pricing + catalog]
│   ├── components-badges.css          [keycaps + badges]
│   ├── components-panels.css          [monolith panels + hero sections]
│   ├── theme-toggle.css               [light/dark conditional CSS]
│   └── [existing CSS files preserved for backward compat]
│
├── components/
│   ├── button.php                     [gano_button($args)]
│   ├── card-pricing.php               [gano_card_pricing($tier)]
│   ├── card-product.php               [gano_card_product($product)]
│   ├── keycap.php                     [gano_keycap($text, $type)]
│   ├── badge.php                      [gano_badge($label)]
│   ├── panel.php                      [gano_panel_monolith($content)]
│   └── hero.php                       [gano_hero($heading, $cta)]
│
├── assets/
│   ├── logo-gano.svg                  [light mode]
│   ├── logo-gano-dark.svg             [dark mode]
│   └── logo-mark.svg                  [symbol only]
│
└── functions.php
    ├── require_once all components/
    ├── wp_enqueue_style() all CSS
    ├── add_filter('body_class', theme toggle)
    └── [existing hooks preserved]
```

---

## 2. Design Tokens & CSS Foundation

### 2.1 Color Tokens

**Light Mode (Marketing):**
```css
--gano-blue: #1B4FD8;
--gano-green: #00C26B;
--gano-orange: #FF6B35;
--gano-white: #FFFFFF;
--gano-gray-50: #F8FAFC;
--gano-gray-100: #E5E7EB;
--gano-gray-900: #05070A;
```

**Dark Mode (SOTA):**
```css
--gano-bg-dark: #0F1115;
--gano-bg-darker: #05080C;
--gano-text-primary: #FFFFFF;
--gano-color-accent: #D4AF37;
```

### 2.2 Typography Tokens

```css
--gano-font-display: 'Plus Jakarta Sans', sans-serif;     [headings 700/800]
--gano-font-body: 'Inter', sans-serif;                    [body 400/600]
--gano-font-mono: 'JetBrains Mono', monospace;            [tech/pricing/keycaps]
```

**Font weights:**
```css
--gano-fw-regular: 400;
--gano-fw-semibold: 600;
--gano-fw-bold: 700;
--gano-fw-extrabold: 800;
```

### 2.3 Spacing & Radii

```css
--gano-radius-sm: 6px;     [keycaps, small buttons]
--gano-radius-md: 8px;     [buttons, inputs]
--gano-radius-lg: 12px;    [cards, pricing]
--gano-radius-xl: 20px;    [catalog cards, monolith panels]
--gano-radius-pill: 50px;  [brand gradient pills]
```

### 2.4 Motion Tokens

```css
--motion-fast: 150ms;      [hover color changes, chip interactions]
--motion-normal: 220ms;    [button transform, card lift]
--motion-slow: 400ms;      [panel slide, gradient shimmer]

/* Easing functions */
ease-out: cubic-bezier(0.4, 0, 0.2, 1);
spring: cubic-bezier(0.16, 1, 0.3, 1);
elastic: cubic-bezier(0.68, -0.55, 0.265, 1.55);
```

### 2.5 Light/Dark Mode Implementation

**Strategy:** Single CSS file with conditional variables via `[data-theme="dark"]` selector.

```css
:root {
    /* Light mode defaults */
    --gano-bg: #FFFFFF;
    --gano-text: #05070A;
    --gano-surface: #F8FAFC;
}

[data-theme="dark"] {
    /* Dark mode overrides */
    --gano-bg: #0F1115;
    --gano-text: #FFFFFF;
    --gano-surface: rgba(255, 255, 255, 0.05);
}
```

**Toggle implementation:** Add class `theme-dark` to `<body>` via PHP filter + save preference to `wp_options`.

---

## 3. PHP Component Functions

### 3.1 Button Component

**File:** `components/button.php`

**Function signature:**
```php
gano_button($args = [])
```

**Parameters:**
- `text` (string): Button label
- `url` (string): Link URL
- `variant` (string): primary | secondary | outline | ghost | gold
- `size` (string): sm | md | lg
- `theme` (string): light | dark
- `icon` (string): Font Awesome class (optional)
- `class` (string): Additional CSS classes

**Output:** `<a class="gano-btn gano-btn--primary gano-btn--md gano-btn--light">...</a>`

**Usage in templates:**
```php
<?php echo gano_button([
    'text' => 'Comenzar Ahora',
    'url' => '/register/',
    'variant' => 'cta',
    'theme' => 'light',
    'icon' => 'fa-arrow-right',
]); ?>
```

**CSS variants:**
- `.gano-btn--primary.gano-btn--light`: Solid blue, white text
- `.gano-btn--cta.gano-btn--light`: Solid orange (primary CTA)
- `.gano-btn--outline.gano-btn--light`: Transparent, blue border
- `.gano-btn--primary.gano-btn--dark`: Gradient blue→indigo, UPPERCASE
- `.gano-btn--ghost.gano-btn--dark`: Transparent, gold border, UPPERCASE
- `.gano-btn--gold.gano-btn--dark`: Solid gold on black, premium variant

### 3.2 Pricing Card Component

**File:** `components/card-pricing.php`

**Function signature:**
```php
gano_card_pricing($tier)
```

**Parameters:**
```php
[
    'name' => 'Núcleo Prime',
    'price' => '$196k',
    'period' => 'COP / año',
    'description' => 'Esencial para startups',
    'features' => ['5 sitios', 'NVMe SSD', 'SSL gratis', '...'],
    'cta_text' => 'Contratar',
    'cta_url' => '/checkout/',
    'featured' => false,   // Set true for middle card (highlighted)
]
```

**Output:** 3-column responsive grid card with:
- Featured variant: 2px blue border, `translateY(-4px)`, gold "Más popular" keycap
- Price display: Bold heading + small period caption
- Feature list: Green checkmarks, bottom-border dividers
- CTA: Orange button (featured) or blue outline (outer cards)

**CSS features:**
- Light background (#FFFFFF)
- 1px gray border (2px blue if featured)
- Smooth hover lift
- Responsive: 1-column mobile, 3-column desktop

### 3.3 Catalog Card Component (Dark SOTA)

**File:** `components/card-product.php`

**Function signature:**
```php
gano_card_product($product)
```

**Parameters:**
```php
[
    'tier_name' => 'Núcleo Prime',
    'title' => 'Nodo Bogotá Premium',  // optional, defaults to tier_name
    'icon' => 'fa-database',
    'specs' => [
        'CPU' => 'Intel Xeon Platinum',
        'RAM' => '64 GB DDR4',
        'STORAGE' => '2 TB NVMe Gen 4',
        'UPTIME' => '99.99% SLA',
    ],
    'price' => '$196k',
    'period' => 'COP / año',
    'cta_text' => 'Explorar',
    'cta_url' => '/shop/nucleoprime/',
    'featured' => false,   // Set true for gold/filled variant
]
```

**Output:** Dark glassomorphic card with:
- Top accent bar (3px gradient blue→orange, scales 0→1 on hover)
- Large icon (purple default, gold on hover)
- Tier eyebrow + title
- Spec rows (mono uppercase labels, white values)
- Price display (small "Desde", bold price, small period)
- Ghost button (gold border) or gold filled (featured variant)
- Hover: `translateY(-15px)`, gold border glow

**CSS features:**
- Backdrop blur + semi-transparent background
- Multi-layer shadow on hover
- Responsive: 1-column mobile, auto-grid desktop

### 3.4 Keycap Component (Signature Visual)

**File:** `components/keycap.php`

**Function signature:**
```php
gano_keycap($text, $type = 'default', $prefix_icon = null)
```

**Parameters:**
- `text` (string): Display text (e.g., "NVMe Gen 4", "99.9% SLA")
- `type` (string): default | gold
- `prefix_icon` (string, optional): Hex color for glowing dot prefix

**Output:** `<span class="gano-keycap"><code>NVMe Gen 4</code></span>`

**CSS features:**
- Multi-layer 3D shadow (inset light top, 2px shadow, glow)
- Gradient background (default: indigo, gold variant: gold)
- Mono font, small size, all-caps
- Glowing dot prefix (when icon provided)

**Usage:**
```php
<?php echo gano_keycap('NVMe Gen 4'); ?>
<?php echo gano_keycap('SOTA', 'gold', '#D4AF37'); ?>
<?php echo gano_keycap('99.9% SLA'); ?>
```

### 3.5 Badge Component

**File:** `components/badge.php`

**Function signature:**
```php
gano_badge($label, $variant = 'default')
```

**Variants:** default | success | warning | feature | premium

**CSS:** Small pill-shaped badge, used for category labels, feature flags, status indicators.

### 3.6 Monolith Panel Component

**File:** `components/panel.php`

**Function signature:**
```php
gano_panel_monolith($content, $heading = null)
```

**CSS features:**
- Dark semi-transparent background with backdrop blur
- Gold top hairline accent
- Multi-layer shadow
- Used for: Hero right panel (benchmarks), checkout summary, SOTA hub sections

### 3.7 Hero Section Component

**File:** `components/hero.php`

**Function signature:**
```php
gano_hero($args = [])
```

**Parameters:**
- `heading` (string): H1 headline
- `subtext` (string): Lead paragraph
- `accent_words` (array): Words to highlight with gradient
- `cta_primary` (array): Primary button args
- `cta_secondary` (array): Secondary button args (optional)
- `stats` (array): Stat rows for bottom section
- `theme` (string): light | dark

---

## 4. Integration with Existing Code

### 4.1 Backward Compatibility

**Preserved:**
- Existing `gano_button()` function (old variant system) — extends with new variants
- All existing CSS files remain untouched (new CSS files added)
- Elementor editor functionality unchanged
- `gano-cta-registro` component (from prior audit) — refactored to use new button component
- All shortcodes remain functional

**Migration path:** Old templates can use new components, or continue with old functions. No forced refactor of existing pages.

### 4.2 Functions.php Changes

**Add at top:**
```php
// Load design system components
require_once get_stylesheet_directory() . '/components/button.php';
require_once get_stylesheet_directory() . '/components/card-pricing.php';
require_once get_stylesheet_directory() . '/components/card-product.php';
require_once get_stylesheet_directory() . '/components/keycap.php';
require_once get_stylesheet_directory() . '/components/badge.php';
require_once get_stylesheet_directory() . '/components/panel.php';
require_once get_stylesheet_directory() . '/components/hero.php';
```

**Add styles:**
```php
// Enqueue design system CSS
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('gano-design-tokens',
        get_stylesheet_directory_uri() . '/css/design-tokens.css',
        [], filemtime(get_stylesheet_directory() . '/css/design-tokens.css')
    );

    wp_enqueue_style('gano-components',
        get_stylesheet_directory_uri() . '/css/components-buttons.css',
        ['gano-design-tokens'],
        filemtime(get_stylesheet_directory() . '/css/components-buttons.css')
    );

    // ... other component CSS files
});
```

**Add theme toggle:**
```php
add_filter('body_class', function($classes) {
    $user_theme = get_user_meta(get_current_user_id(), 'gano_theme_preference', true);
    if ($user_theme === 'dark' || (empty($user_theme) && current_hour() >= 18)) {
        $classes[] = 'theme-dark';
    }
    return $classes;
});
```

### 4.3 Logo System

**Replace in header/footer:**

Old:
```php
<img src="/logo.png" alt="Gano Digital">
```

New:
```php
<?php
$is_dark = has_class('theme-dark');
$logo = $is_dark ? 'logo-gano-dark.svg' : 'logo-gano.svg';
?>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/<?php echo $logo; ?>"
     alt="Gano Digital" class="gano-logo">
```

---

## 5. Testing Strategy

### 5.1 Pre-Deployment (Local)

**Components render without errors:**
- [ ] Each PHP component file parses without syntax errors
- [ ] All CSS files compile without errors
- [ ] No undefined function warnings

**Visual correctness:**
- [ ] Light mode: all buttons, pricing cards, keycaps render with correct colors + fonts
- [ ] Dark mode: toggle to dark theme, verify all components adapt
- [ ] Responsive: test at 375px (mobile), 768px (tablet), 1440px (desktop)

**Interactive elements:**
- [ ] Buttons: hover lift, color transitions smooth
- [ ] Cards: hover lift, shadow glow, accent bar reveal
- [ ] Theme toggle: persists preference to user meta, page reloads with correct theme

**Accessibility:**
- [ ] Focus states: outline visible on all interactive elements
- [ ] Color contrast: WCAG AA minimum (4.5:1 for text)
- [ ] prefers-reduced-motion: animations disabled on users with reduced motion setting

**Browser compatibility:**
- [ ] Chrome/Edge (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)

### 5.2 Post-Deployment (Production)

**Smoke test (15 min):**
- [ ] Visit https://gano.digital/ → renders with new logo + design
- [ ] Check /ecosistemas/ → catalog cards display correctly in light mode
- [ ] Check /shop/ (or equivalent dark page) → SOTA design visible
- [ ] Toggle theme → dark mode activates, preference saves
- [ ] Click buttons → links work, no console errors
- [ ] Test on mobile → responsive, no layout breaks

**Verification:**
- [ ] All pages HTTP 200 OK
- [ ] No CSS 404s in browser DevTools
- [ ] No JS errors in console
- [ ] Elementor editor still functional (try adding a page)

**Rollback if needed:**
```bash
git revert <commit-hash>
# Webhook auto-deploys, old design restored in 5 min
```

---

## 6. Deployment Process

### 6.1 Branch Workflow

```bash
git checkout -b feature/claude-design-system-integration

# Add all files (CSS, components, assets)
git add wp-content/themes/gano-child/css/*.css
git add wp-content/themes/gano-child/components/*.php
git add wp-content/themes/gano-child/assets/*.svg
git add wp-content/themes/gano-child/functions.php

git commit -m "feat: Claude Design System integration

- Add design tokens (colors, typography, spacing, motion)
- Implement reusable component functions (button, cards, keycaps, badges, panels, hero)
- Add light/dark theme toggle system
- Replace logos with new brand assets
- Preserve backward compatibility with existing components
- All components tested locally and ready for production

Components added:
- gano_button() with light/dark variants
- gano_card_pricing() for 3-tier pricing display
- gano_card_product() for dark SOTA catalog
- gano_keycap() for signature 3D badge effect
- gano_badge() for labels and status
- gano_panel_monolith() for dark sections
- gano_hero() for hero sections with gradient accents

CSS additions:
- design-tokens.css (from Claude Design System)
- components-buttons.css
- components-cards.css
- components-badges.css
- components-panels.css
- theme-toggle.css

Co-Authored-By: Claude Sonnet 4.6 <noreply@anthropic.com>"

git push -u origin feature/claude-design-system-integration
```

### 6.2 GitHub PR

Create PR with:
- **Title:** "feat: Claude Design System integration (full component library)"
- **Description:** Component list, testing results, rollback plan
- **Base branch:** main
- **Target:** Code review → approved → squash merge

### 6.3 Webhook Deployment

Once merged to main:
1. GitHub Actions webhook triggers
2. Runs rsync to deploy gano-child/ to server
3. Deployment complete in ~2-3 minutes
4. Server reflects new design system

### 6.4 Manual Verification

Post-deployment (on production):
1. Check https://gano.digital/ in browser
2. Verify logo, colors, buttons match design
3. Test light/dark toggle
4. Check mobile responsiveness
5. Confirm no console errors
6. Test old components (backward compat)

---

## 7. Success Criteria

**Design system is successfully integrated when:**

- ✅ All new component functions load without errors
- ✅ Design tokens CSS applied globally (colors, fonts, spacing match spec)
- ✅ Light mode: marketing pages display with blue/green/orange palette
- ✅ Dark mode: SOTA pages display with gold/indigo on dark monolith
- ✅ Theme toggle: users can switch light/dark, preference persists
- ✅ Logos: new brand assets used in header/footer
- ✅ Responsive: layout works on 375px–1920px viewports
- ✅ Accessible: WCAG AA color contrast, focus states visible, reduced motion respected
- ✅ Backward compatible: old components still work (no breaking changes)
- ✅ Performance: no new CSS/JS performance regressions
- ✅ Production: zero critical errors in server logs post-deployment

---

## 8. Open Decisions (for Diego)

1. **Theme default:** Light mode by default, or detect user preference (time-of-day, prefers-color-scheme)?
2. **Logo placement:** Header only, or also in footer + brand accent areas?
3. **Old vs new components:** Gradually migrate existing pages to new components, or leave as-is?
4. **Elementor integration:** Update Elementor button styles to match design system?

---

## 9. Known Constraints

- .gitattributes line ending normalization still pending webhook sync (~2+ days lag observed)
- GoDaddy WAF blocks curl/automated tools (HTTP 403 false positive) — doesn't affect users
- PHP 5.6+ required (WordPress minimum)
- No Tailwind CSS (design system uses vanilla CSS variables only)

---

## 10. Future Enhancements (Post-Launch)

- [ ] Elementor widget library (drag-drop components in page builder)
- [ ] Dark mode toggle button UI component
- [ ] Animation library (add to motion tokens)
- [ ] Component living documentation page (/design/)
- [ ] Figma design file linked to this system

---

**Document status:** ✅ **APPROVED FOR IMPLEMENTATION**

**Next step:** Execute via implementation plan (writing-plans skill)
