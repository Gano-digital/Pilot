# Website Layout Documentation

## Overview

The Website layout provides the structural container for website pages. For most applications, you should use **RootLayout** instead, which wraps this component and provides centralized header/footer management.

## RECOMMENDED: Use RootLayout Pattern

**For multi-page applications, use RootLayout to maintain consistent navigation across all pages.**

### Correct Implementation ✅

**Step 1: Use RootLayout in `App.tsx`**

```tsx
import RootLayout from "./layouts/RootLayout";

const router = createBrowserRouter([
  {
    path: "/",
    element: (
      <RootLayout>
        <Outlet />
      </RootLayout>
    ),
    children: routes,
  },
]);
```

**Step 2: Create simple page components**

```tsx
// src/pages/index.tsx
export default function HomePage() {
  return (
    <div className="container mx-auto px-4 py-16">
      <h1>Welcome to MyApp</h1>
      <p>Your content here...</p>
    </div>
  );
}

// src/pages/about.tsx
export default function AboutPage() {
  return (
    <div className="container mx-auto px-4 py-16">
      <h1>About Us</h1>
      <p>Your content here...</p>
    </div>
  );
}
```

**Step 3: Customize header and footer by editing the component files**

- **Header**: Edit `src/layouts/parts/Header.tsx`
- **Footer**: Edit `src/layouts/parts/Footer.tsx`

### Benefits of RootLayout Pattern

1. **Consistency**: Header and footer are centralized in RootLayout
2. **Maintainability**: Update navigation in one place, applies to all pages
3. **Simplicity**: Page components focus only on content
4. **Common Pattern**: Follows React Router and Next.js conventions

## Direct Website Component Usage (Advanced)

For special cases where you need custom layout behavior, you can use the Website component directly:

```tsx
import Website from "@/layouts/Website";
import Header from "@/layouts/parts/Header";
import Footer from "@/layouts/parts/Footer";

export default function CustomPage() {
  return (
    <Website>
      <Header />
      <main>
        <div>Your custom content</div>
      </main>
      <Footer />
    </Website>
  );
}
```

## Mobile Responsiveness

The layout automatically handles mobile responsiveness:

- Header navigation collapses into a mobile menu
- Footer columns stack vertically on small screens
- Content areas adjust padding and spacing

## Important Notes

1. **Use RootLayout for multi-page apps**: Define header/footer once in RootLayout
2. **Keep pages simple**: Let RootLayout handle layout concerns
3. **Centralized customization**: Edit Header.tsx and Footer.tsx directly
4. **Maintain consistency**: Same header/footer across all pages

## Anti-Patterns to Avoid

❌ **Don't create custom layout structure**

```tsx
// Bad
<div className="min-h-screen">
  <header>...</header>
  <main>...</main>
  <footer>...</footer>
</div>
```

✅ **Do use provided layout components**

```tsx
// Good
<RootLayout>
  <YourPageContent />
</RootLayout>
```
