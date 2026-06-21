import { cn } from "@/lib/utils";
import { Button } from "@/components/ui/button";
import {
  Menu,
  X,
  ChevronDown,
  Search,
  Bell,
  User,
  Settings,
  LogOut,
  HelpCircle
} from "lucide-react";
import { useState } from "react";

export interface DashboardConfig {
  sidebar?: {
    logo?: {
      text?: string;
      image?: string;
      href?: string;
    };
    navigation?: {
      main?: Array<{
        title: string;
        href: string;
        icon?: React.ComponentType<{ className?: string }>;
        active?: boolean;
        badge?: string | number;
      }>;
      secondary?: Array<{
        title: string;
        href: string;
        icon?: React.ComponentType<{ className?: string }>;
      }>;
    };
    footer?: React.ReactNode;
    className?: string;
  };
  header?: {
    search?: {
      enabled?: boolean;
      placeholder?: string;
    };
    notifications?: {
      enabled?: boolean;
      count?: number;
    };
    user?: {
      name?: string;
      email?: string;
      avatar?: string;
      initials?: string;
    };
    actions?: React.ReactNode;
    className?: string;
  };
  main?: {
    maxWidth?: "sm" | "md" | "lg" | "xl" | "2xl" | "full";
    padding?: boolean;
    className?: string;
  };
}

interface DashboardProps {
  children: React.ReactNode;
  config?: DashboardConfig;
  className?: string;
}

const maxWidthClasses = {
  sm: "max-w-screen-sm",
  md: "max-w-screen-md",
  lg: "max-w-screen-lg",
  xl: "max-w-screen-xl",
  "2xl": "max-w-screen-2xl",
  full: "max-w-full",
};

export default function Dashboard({
  children,
  config = {},
  className
}: DashboardProps) {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [userMenuOpen, setUserMenuOpen] = useState(false);

  const {
    sidebar = {},
    header = {},
    main = {}
  } = config;

  const {
    logo = { text: "Dashboard" },
    navigation = {},
    footer: sidebarFooter,
    className: sidebarClassName
  } = sidebar;

  const {
    search = { enabled: true, placeholder: "Search..." },
    notifications = { enabled: true, count: 0 },
    user = { name: "User", email: "user@example.com", initials: "U" },
    actions: headerActions,
    className: headerClassName
  } = header;

  const {
    maxWidth = "full",
    padding = true,
    className: mainClassName
  } = main;

  return (
    <div className={cn("flex h-screen bg-gray-50/50", className)}>
      {/* Sidebar */}
      <aside className={cn(
        "fixed inset-y-0 left-0 z-50 w-64 bg-white border-r transform transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:inset-0",
        sidebarOpen ? "translate-x-0" : "-translate-x-full",
        sidebarClassName
      )}>
        <div className="flex h-full flex-col">
          {/* Sidebar Header */}
          <div className="flex h-14 items-center justify-between px-4 border-b">
            {logo.href ? (
              <a href={logo.href} className="flex items-center gap-2 font-semibold">
                {logo.image && (
                  <img src={logo.image} alt={logo.text} className="h-6 w-6" />
                )}
                {logo.text && <span>{logo.text}</span>}
              </a>
            ) : (
              <div className="flex items-center gap-2 font-semibold">
                {logo.image && (
                  <img src={logo.image} alt={logo.text} className="h-6 w-6" />
                )}
                {logo.text && <span>{logo.text}</span>}
              </div>
            )}
            <Button
              variant="ghost"
              size="icon"
              className="lg:hidden"
              onClick={() => setSidebarOpen(false)}
            >
              <X className="h-4 w-4" />
            </Button>
          </div>

          {/* Sidebar Navigation */}
          <nav className="flex-1 space-y-1 px-2 py-4 overflow-y-auto">
            {navigation.main && navigation.main.length > 0 && (
              <div className="space-y-1">
                {navigation.main.map((item, index) => {
                  const Icon = item.icon;
                  return (
                    <a
                      key={index}
                      href={item.href}
                      className={cn(
                        "flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition-colors",
                        item.active
                          ? "bg-gray-100 text-gray-900"
                          : "text-gray-600 hover:bg-gray-50 hover:text-gray-900"
                      )}
                    >
                      {Icon && <Icon className="h-4 w-4" />}
                      <span className="flex-1">{item.title}</span>
                      {item.badge !== undefined && (
                        <span className="ml-auto flex h-5 min-w-[20px] items-center justify-center rounded-full bg-gray-200 px-1 text-xs">
                          {item.badge}
                        </span>
                      )}
                    </a>
                  );
                })}
              </div>
            )}

            {navigation.secondary && navigation.secondary.length > 0 && (
              <>
                <div className="my-4 border-t" />
                <div className="space-y-1">
                  {navigation.secondary.map((item, index) => {
                    const Icon = item.icon;
                    return (
                      <a
                        key={index}
                        href={item.href}
                        className="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-50 hover:text-gray-900"
                      >
                        {Icon && <Icon className="h-4 w-4" />}
                        <span>{item.title}</span>
                      </a>
                    );
                  })}
                </div>
              </>
            )}
          </nav>

          {/* Sidebar Footer */}
          {sidebarFooter && (
            <div className="border-t p-4">
              {sidebarFooter}
            </div>
          )}
        </div>
      </aside>

      {/* Mobile sidebar overlay */}
      {sidebarOpen && (
        <div
          className="fixed inset-0 z-40 bg-black/50 lg:hidden"
          onClick={() => setSidebarOpen(false)}
        />
      )}

      {/* Main Content Area */}
      <div className="flex flex-1 flex-col overflow-hidden">
        {/* Header */}
        <header className={cn(
          "flex h-14 items-center gap-4 border-b bg-white px-4 lg:px-6",
          headerClassName
        )}>
          {/* Mobile menu button */}
          <Button
            variant="ghost"
            size="icon"
            className="lg:hidden"
            onClick={() => setSidebarOpen(true)}
          >
            <Menu className="h-5 w-5" />
          </Button>

          {/* Search */}
          {search.enabled && (
            <div className="flex-1 max-w-md">
              <div className="relative">
                <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-gray-400" />
                <input
                  type="search"
                  placeholder={search.placeholder}
                  className="w-full rounded-lg border bg-gray-50 pl-8 pr-3 py-2 text-sm outline-none focus:bg-white focus:ring-2 focus:ring-gray-200"
                />
              </div>
            </div>
          )}

          {/* Header Actions */}
          <div className="ml-auto flex items-center gap-2">
            {headerActions}

            {/* Notifications */}
            {notifications.enabled && (
              <Button variant="ghost" size="icon" className="relative">
                <Bell className="h-4 w-4" />
                {notifications.count && notifications.count > 0 && (
                  <span className="absolute -top-1 -right-1 h-4 w-4 rounded-full bg-red-500 text-[10px] font-medium text-white flex items-center justify-center">
                    {notifications.count > 9 ? "9+" : notifications.count}
                  </span>
                )}
              </Button>
            )}

            {/* User Menu */}
            <div className="relative">
              <Button
                variant="ghost"
                className="relative flex items-center gap-2 px-2"
                onClick={() => setUserMenuOpen(!userMenuOpen)}
              >
                {user.avatar ? (
                  <img
                    src={user.avatar}
                    alt={user.name}
                    className="h-8 w-8 rounded-full"
                  />
                ) : (
                  <div className="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-sm font-medium">
                    {user.initials}
                  </div>
                )}
                <div className="hidden lg:block text-left">
                  <div className="text-sm font-medium">{user.name}</div>
                  <div className="text-xs text-gray-500">{user.email}</div>
                </div>
                <ChevronDown className="h-4 w-4 text-gray-500 hidden lg:block" />
              </Button>

              {/* User Dropdown Menu */}
              {userMenuOpen && (
                <>
                  <div
                    className="fixed inset-0 z-10"
                    onClick={() => setUserMenuOpen(false)}
                  />
                  <div className="absolute right-0 top-full z-50 mt-2 w-56 rounded-lg border bg-white shadow-lg">
                    <div className="p-2">
                      <div className="px-2 py-1.5 text-sm text-gray-900">
                        <div className="font-medium">{user.name}</div>
                        <div className="text-gray-500">{user.email}</div>
                      </div>
                      <div className="my-1 border-t" />
                      <button className="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100">
                        <User className="h-4 w-4" />
                        Profile
                      </button>
                      <button className="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100">
                        <Settings className="h-4 w-4" />
                        Settings
                      </button>
                      <button className="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100">
                        <HelpCircle className="h-4 w-4" />
                        Help
                      </button>
                      <div className="my-1 border-t" />
                      <button className="flex w-full items-center gap-2 rounded px-2 py-1.5 text-sm text-gray-700 hover:bg-gray-100">
                        <LogOut className="h-4 w-4" />
                        Log out
                      </button>
                    </div>
                  </div>
                </>
              )}
            </div>
          </div>
        </header>

        {/* Page Content */}
        <main className={cn(
          "flex-1 overflow-y-auto",
          padding && "p-4 lg:p-6",
          mainClassName
        )}>
          <div className={cn(
            "mx-auto",
            maxWidthClasses[maxWidth]
          )}>
            {children}
          </div>
        </main>
      </div>
    </div>
  );
}
