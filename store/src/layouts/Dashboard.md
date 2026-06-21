# Dashboard Layout Documentation

## Overview
The Dashboard layout provides a complete application shell with sidebar navigation, header with search and user menu, and main content area. Perfect for admin panels, SaaS applications, and internal tools.

## CRITICAL: Required Usage Pattern

**ALL DASHBOARD PAGES MUST USE THIS LAYOUT** - Never create dashboard pages with their own navigation or layout structure.

### Correct Implementation ✅
```tsx
import Dashboard from '@/layouts/Dashboard';
import { Home, Users, Settings, BarChart } from 'lucide-react';

export default function DashboardPage() {
  return (
    <Dashboard config={{
      sidebar: {
        logo: { text: 'My App' },
        navigation: {
          main: [
            { title: 'Dashboard', href: '/', icon: Home, active: true },
            { title: 'Users', href: '/users', icon: Users, badge: 12 },
            { title: 'Analytics', href: '/analytics', icon: BarChart },
            { title: 'Settings', href: '/settings', icon: Settings }
          ]
        }
      },
      header: {
        user: {
          name: 'John Doe',
          email: 'john@example.com',
          initials: 'JD'
        }
      }
    }}>
      {/* Dashboard content goes here */}
      <div className="space-y-6">
        <h1 className="text-3xl font-bold">Dashboard</h1>
        {/* Your dashboard components */}
      </div>
    </Dashboard>
  );
}
```

### Incorrect Implementation ❌
```tsx
// DON'T DO THIS - Creating your own dashboard structure
export default function DashboardPage() {
  return (
    <div className="flex h-screen">
      <aside>...</aside>
      <main>...</main>
    </div>
  );
}
```

## Configuration Options

### Sidebar Configuration

```typescript
interface SidebarConfig {
  logo?: {
    text?: string;           // Logo text
    image?: string;          // Logo image URL
    href?: string;           // Logo link
  };
  navigation?: {
    main?: Array<{           // Primary navigation items
      title: string;
      href: string;
      icon?: React.ComponentType;
      active?: boolean;
      badge?: string | number;
    }>;
    secondary?: Array<{      // Secondary navigation items
      title: string;
      href: string;
      icon?: React.ComponentType;
    }>;
  };
  footer?: React.ReactNode;  // Sidebar footer content
  className?: string;        // Additional CSS classes
}
```

### Header Configuration

```typescript
interface HeaderConfig {
  search?: {
    enabled?: boolean;       // Show search box (default: true)
    placeholder?: string;    // Search placeholder text
  };
  notifications?: {
    enabled?: boolean;       // Show notifications bell (default: true)
    count?: number;         // Notification count
  };
  user?: {
    name?: string;          // User display name
    email?: string;         // User email
    avatar?: string;        // Avatar image URL
    initials?: string;      // Fallback initials
  };
  actions?: React.ReactNode; // Additional header actions
  className?: string;       // Additional CSS classes
}
```

### Main Content Configuration

```typescript
interface MainConfig {
  maxWidth?: "sm" | "md" | "lg" | "xl" | "2xl" | "full"; // Content max width
  padding?: boolean;        // Add padding (default: true)
  className?: string;       // Additional CSS classes
}
```

## Complete Examples

### Full-Featured Dashboard

```tsx
import Dashboard from '@/layouts/Dashboard';
import { 
  Home, 
  Users, 
  FileText, 
  BarChart3, 
  Settings,
  CreditCard,
  HelpCircle,
  LogOut
} from 'lucide-react';
import { Button } from '@/components/ui/button';

export default function AdminDashboard() {
  return (
    <Dashboard config={{
      sidebar: {
        logo: {
          text: 'AdminPanel',
          image: '/logo.svg',
          href: '/'
        },
        navigation: {
          main: [
            { 
              title: 'Dashboard', 
              href: '/', 
              icon: Home, 
              active: true 
            },
            { 
              title: 'Users', 
              href: '/users', 
              icon: Users,
              badge: 128
            },
            { 
              title: 'Documents', 
              href: '/documents', 
              icon: FileText,
              badge: 'New'
            },
            { 
              title: 'Analytics', 
              href: '/analytics', 
              icon: BarChart3 
            },
            { 
              title: 'Billing', 
              href: '/billing', 
              icon: CreditCard 
            }
          ],
          secondary: [
            { 
              title: 'Settings', 
              href: '/settings', 
              icon: Settings 
            },
            { 
              title: 'Help', 
              href: '/help', 
              icon: HelpCircle 
            }
          ]
        },
        footer: (
          <div className="space-y-2">
            <div className="text-xs text-gray-500">Free Plan</div>
            <Button size="sm" className="w-full">
              Upgrade to Pro
            </Button>
          </div>
        )
      },
      header: {
        search: {
          enabled: true,
          placeholder: 'Search users, documents...'
        },
        notifications: {
          enabled: true,
          count: 3
        },
        user: {
          name: 'John Doe',
          email: 'john@example.com',
          avatar: '/avatar.jpg',
          initials: 'JD'
        },
        actions: (
          <Button variant="outline" size="sm">
            Quick Action
          </Button>
        )
      },
      main: {
        maxWidth: '2xl',
        padding: true
      }
    }}>
      {/* Dashboard Content */}
      <div className="space-y-6">
        {/* Page Header */}
        <div>
          <h1 className="text-3xl font-bold">Dashboard</h1>
          <p className="text-gray-500">Welcome back, John!</p>
        </div>

        {/* Stats Cards */}
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
          <Card>
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
              <CardTitle className="text-sm font-medium">Total Revenue</CardTitle>
              <DollarSign className="h-4 w-4 text-muted-foreground" />
            </CardHeader>
            <CardContent>
              <div className="text-2xl font-bold">$45,231.89</div>
              <p className="text-xs text-muted-foreground">
                +20.1% from last month
              </p>
            </CardContent>
          </Card>
          {/* More stats cards... */}
        </div>

        {/* Main Content */}
        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
          <Card className="col-span-4">
            <CardHeader>
              <CardTitle>Overview</CardTitle>
            </CardHeader>
            <CardContent>
              {/* Chart or content */}
            </CardContent>
          </Card>
          <Card className="col-span-3">
            <CardHeader>
              <CardTitle>Recent Activity</CardTitle>
            </CardHeader>
            <CardContent>
              {/* Activity list */}
            </CardContent>
          </Card>
        </div>
      </div>
    </Dashboard>
  );
}
```

### Minimal Dashboard

```tsx
import Dashboard from '@/layouts/Dashboard';
import { Home, Settings } from 'lucide-react';

export default function SimpleDashboard() {
  return (
    <Dashboard config={{
      sidebar: {
        logo: { text: 'MyApp' },
        navigation: {
          main: [
            { title: 'Home', href: '/', icon: Home, active: true },
            { title: 'Settings', href: '/settings', icon: Settings }
          ]
        }
      },
      header: {
        search: { enabled: false },
        notifications: { enabled: false },
        user: { name: 'User', initials: 'U' }
      }
    }}>
      <div>
        <h1 className="text-2xl font-bold mb-4">Welcome</h1>
        <p>Your content here</p>
      </div>
    </Dashboard>
  );
}
```

### Analytics Dashboard

```tsx
import Dashboard from '@/layouts/Dashboard';
import { 
  BarChart3, 
  PieChart, 
  TrendingUp, 
  Calendar,
  Download,
  Filter
} from 'lucide-react';
import { Button } from '@/components/ui/button';

export default function AnalyticsDashboard() {
  return (
    <Dashboard config={{
      sidebar: {
        logo: { text: 'Analytics' },
        navigation: {
          main: [
            { title: 'Overview', href: '/', icon: BarChart3, active: true },
            { title: 'Reports', href: '/reports', icon: PieChart },
            { title: 'Trends', href: '/trends', icon: TrendingUp },
            { title: 'Schedule', href: '/schedule', icon: Calendar }
          ]
        }
      },
      header: {
        actions: (
          <>
            <Button variant="outline" size="sm">
              <Filter className="h-4 w-4 mr-2" />
              Filter
            </Button>
            <Button variant="outline" size="sm">
              <Download className="h-4 w-4 mr-2" />
              Export
            </Button>
          </>
        )
      }
    }}>
      <div className="space-y-4">
        {/* Date Range Selector */}
        <div className="flex items-center justify-between">
          <h1 className="text-2xl font-bold">Analytics Overview</h1>
          <div className="flex gap-2">
            <Button variant="outline" size="sm">Today</Button>
            <Button variant="outline" size="sm">Week</Button>
            <Button variant="outline" size="sm">Month</Button>
            <Button variant="outline" size="sm">Year</Button>
          </div>
        </div>

        {/* Charts Grid */}
        <div className="grid gap-4 md:grid-cols-2">
          {/* Chart components */}
        </div>
      </div>
    </Dashboard>
  );
}
```

## Mobile Responsiveness

The Dashboard layout is fully responsive:
- **Sidebar**: Becomes a slide-out drawer on mobile with hamburger menu
- **Search**: Adapts width on smaller screens
- **User Menu**: Simplified on mobile, showing only avatar
- **Content**: Adjusts padding and spacing for mobile

## Navigation Patterns

### Active State Management
```tsx
// Determine active state based on current route
const currentPath = window.location.pathname;

navigation: {
  main: [
    { 
      title: 'Dashboard', 
      href: '/', 
      icon: Home, 
      active: currentPath === '/'
    },
    { 
      title: 'Users', 
      href: '/users', 
      icon: Users,
      active: currentPath.startsWith('/users')
    }
  ]
}
```

### Badge Usage
```tsx
// Numeric badges for counts
{ title: 'Messages', href: '/messages', badge: 24 }

// Text badges for status
{ title: 'Updates', href: '/updates', badge: 'New' }
```

## Best Practices

1. **Consistent Navigation**: Keep navigation items consistent across all dashboard pages
2. **Active States**: Always indicate the current active page in navigation
3. **User Information**: Display user name and avatar for personalization
4. **Responsive Design**: Test on mobile devices to ensure drawer works properly
5. **Loading States**: Add loading indicators for async content
6. **Error Boundaries**: Wrap content in error boundaries for resilience

## Common Patterns

### Dashboard with Tabs
```tsx
<Dashboard config={dashboardConfig}>
  <div className="space-y-4">
    <Tabs defaultValue="overview">
      <TabsList>
        <TabsTrigger value="overview">Overview</TabsTrigger>
        <TabsTrigger value="analytics">Analytics</TabsTrigger>
        <TabsTrigger value="reports">Reports</TabsTrigger>
      </TabsList>
      <TabsContent value="overview">
        {/* Overview content */}
      </TabsContent>
      <TabsContent value="analytics">
        {/* Analytics content */}
      </TabsContent>
      <TabsContent value="reports">
        {/* Reports content */}
      </TabsContent>
    </Tabs>
  </div>
</Dashboard>
```

### Dashboard with Breadcrumbs
```tsx
<Dashboard config={dashboardConfig}>
  <div className="space-y-4">
    {/* Breadcrumbs */}
    <nav className="flex" aria-label="Breadcrumb">
      <ol className="inline-flex items-center space-x-1">
        <li>
          <a href="/" className="text-gray-500 hover:text-gray-700">
            Dashboard
          </a>
        </li>
        <li>
          <span className="mx-2 text-gray-400">/</span>
          <a href="/users" className="text-gray-500 hover:text-gray-700">
            Users
          </a>
        </li>
        <li>
          <span className="mx-2 text-gray-400">/</span>
          <span className="text-gray-700">Profile</span>
        </li>
      </ol>
    </nav>

    {/* Page content */}
    <div>
      <h1 className="text-2xl font-bold">User Profile</h1>
      {/* Profile content */}
    </div>
  </div>
</Dashboard>
```

## Anti-Patterns to Avoid

❌ **Don't create multiple dashboard layouts**
❌ **Don't implement custom navigation systems**
❌ **Don't forget to set active states**
❌ **Don't hardcode user information**
❌ **Don't skip mobile testing**

## Integration with Other Components

The Dashboard layout works seamlessly with all shadcn/ui components:
