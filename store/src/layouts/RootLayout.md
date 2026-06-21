# RootLayout Documentation

## Overview

RootLayout is the recommended way to create consistent multi-page **websites** (marketing sites, landing pages, public-facing apps). It wraps all your pages with a centralized header and footer.

**Important**:

- **For websites**: Use RootLayout (includes header and footer)
- **For dashboards/admin panels**: Use the `Dashboard` component instead (includes sidebar navigation and top bar)
- **For games/fullscreen apps**: Remove `<Header />` and `<Footer />` from RootLayout

## Why Use RootLayout?

### Problems It Solves

1. **Consistency**: Header and footer appear on every page automatically
2. **Maintainability**: Update navigation in Header.tsx, applies everywhere
3. **Simplicity**: Page components focus only on content
4. **Standard Pattern**: Follows React Router and Next.js conventions

### RootLayout Pattern

```tsx
// ✅ Use RootLayout in App.tsx
// src/App.tsx
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

// src/pages/home.tsx - Just content!
export default function HomePage() {
  return <div>Home content</div>;
}

// src/pages/about.tsx - Just content!
export default function AboutPage() {
  return <div>About content</div>;
}
```

### Customizing Header and Footer

To customize the header or footer, directly edit the component files:

- **Header**: Edit `src/layouts/parts/Header.tsx`
- **Footer**: Edit `src/layouts/parts/Footer.tsx`

### Customizing Site Title and Default Metadata

RootLayout renders a `<Helmet>` with the site-wide default `<title>` and `<meta name="description">`. To change the site title or default description, edit the `<Helmet>` block in `src/layouts/RootLayout.tsx`. Per-page `<Helmet>` blocks in route components override these defaults (last-mounted wins). Do not add SEO head tags (`<title>`, `<meta name="description">`, canonical, Open Graph, JSON-LD, etc.) to `index.html` — head management for those is owned by react-helmet at render time. Static shell tags that must be in the initial HTML (`<meta charset>`, `<meta name="viewport">`, `<link rel="icon">`) stay in `index.html`.

**Example - Updating navigation in Header.tsx:**

```tsx
const navItems = [
  { href: "/", label: "Home" },
  { href: "/about", label: "About" },
  { href: "/services", label: "Services" },
  { href: "/contact", label: "Contact" },
];
```

## Setup Guide

### Step 1: Use RootLayout in App.tsx

Open `src/App.tsx` and wrap your routes with RootLayout:

```tsx
import { createBrowserRouter, RouterProvider, Outlet } from "react-router-dom";
import RootLayout from "./layouts/RootLayout";
import { routes } from "./routes";

// Apply to all routes
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

export default function App() {
  return <RouterProvider router={router} />;
}
```

### Step 2: Create Simple Pages

Your page components now just render content:

```tsx
// src/pages/home.tsx
export default function HomePage() {
  return (
    <div className="container mx-auto px-4 py-16">
      <h1 className="text-4xl font-bold mb-4">Welcome to MyApp</h1>
      <p className="text-xl">Your content here...</p>
    </div>
  );
}

// src/pages/about.tsx
export default function AboutPage() {
  return (
    <div className="container mx-auto px-4 py-16">
      <h1 className="text-4xl font-bold mb-4">About Us</h1>
      <p className="text-xl">Learn more about our company...</p>
    </div>
  );
}
```

### Step 3: Customize Header and Footer

Edit the header and footer components directly:

**Header (`src/layouts/parts/Header.tsx`):**

```tsx
const navItems = [
  { href: "/", label: "Home" },
  { href: "/about", label: "About" },
  { href: "/services", label: "Services" },
  { href: "/contact", label: "Contact" },
];
```

**Footer (`src/layouts/parts/Footer.tsx`):**

```tsx
const currentYear = new Date().getFullYear();

return (
  <footer className="mt-auto border-t border-border bg-background">
    <div className="container mx-auto px-4 py-6">
      <div className="flex flex-col md:flex-row justify-between items-center gap-4">
        <div className="text-sm text-muted-foreground">
          © {currentYear} MyApp. All rights reserved.
        </div>
        {/* Add more footer content here */}
      </div>
    </div>
  </footer>
);
```

### Step 4: Add Routes

Update `src/routes.tsx` to include your pages:

```tsx
import { RouteObject } from "react-router-dom";
import { lazy } from "react";
import HomePage from "./pages/index";

const AboutPage = lazy(() => import("./pages/about"));
const ServicesPage = lazy(() => import("./pages/services"));
const ContactPage = lazy(() => import("./pages/contact"));

export const routes: RouteObject[] = [
  { path: "/", element: <HomePage /> },
  { path: "/about", element: <AboutPage /> },
  { path: "/services", element: <ServicesPage /> },
  { path: "/contact", element: <ContactPage /> },
];
```

## Common Use Cases

### Using Dashboard Layout for Admin Panels

For dashboards and admin panels, use the dedicated `Dashboard` component instead of RootLayout:

```tsx
// src/App.tsx
import Dashboard from "./layouts/Dashboard";

const dashboardConfig = {
  sidebar: {
    logo: { text: "Admin Panel" },
    navigation: {
      main: [
        { title: "Dashboard", href: "/dashboard", icon: Home, active: true },
        { title: "Users", href: "/users", icon: Users },
        { title: "Settings", href: "/settings", icon: Settings },
      ],
    },
  },
};

const router = createBrowserRouter([
  {
    path: "/",
    element: (
      <Dashboard config={dashboardConfig}>
        <Outlet />
      </Dashboard>
    ),
    children: dashboardRoutes,
  },
]);
```

### Removing Header/Footer for Games or Fullscreen Apps

For games or single-page apps that need fullscreen layout:

```tsx
// src/layouts/RootLayout.tsx
export default function RootLayout({ children }: RootLayoutProps) {
  return (
    <Website>
      {/* Remove Header and Footer components */}
      {children}
    </Website>
  );
}
```

### Per-Route Customization

For pages that need different layouts, you can create additional route groups:

```tsx
const router = createBrowserRouter([
  {
    // Pages with header/footer
    path: "/",
    element: (
      <RootLayout>
        <Outlet />
      </RootLayout>
    ),
    children: [
      { path: "/", element: <HomePage /> },
      { path: "/about", element: <AboutPage /> },
    ],
  },
  {
    // Fullscreen page without header/footer
    path: "/game",
    element: <GamePage />,
  },
]);
```

## Best Practices

1. **Edit components directly**: Update Header.tsx and Footer.tsx instead of passing props
2. **Keep pages simple**: Pages should only contain content
3. **Use semantic HTML**: Structure content with proper heading hierarchy
4. **Mobile-first**: The layout is responsive by default
5. **Update navigation**: When adding pages, update navItems in Header.tsx

## Troubleshooting

### Navigation not updating

Make sure you updated `navItems` in `Header.tsx` and that the `href` matches your route path.

### Header/Footer not showing

Verify that `<Header />` and `<Footer />` are included in RootLayout.tsx.

### Styling issues

Pages should not include `min-h-screen` or full-page containers. Let RootLayout handle the layout structure.
