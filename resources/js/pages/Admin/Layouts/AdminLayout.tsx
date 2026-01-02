import { ToastContainer } from '@/components/ui/Toast';
import { cn } from '@/lib/utils';
import { Link, router, usePage } from '@inertiajs/react';
import { LogOut, Menu, X } from 'lucide-react';
import { PropsWithChildren, useEffect, useState } from 'react';

interface PageProps {
    auth: {
        user?: {
            name: string;
        };
    };
    flash?: {
        success?: string;
        error?: string;
    };
    [key: string]: unknown;
}

interface Toast {
    id: string;
    message: string;
    type: 'success' | 'error';
}

export default function AdminLayout({ children }: PropsWithChildren) {
    const page = usePage<PageProps>();
    const { auth, flash } = page.props;
    const url = page.url as string;
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [menuOpen, setMenuOpen] = useState(false);
    const [toasts, setToasts] = useState<Toast[]>([]);

    useEffect(() => {
        if (flash?.success || flash?.error) {
            const newToast: Toast = {
                id: Date.now().toString(),
                message: flash.success || flash.error || '',
                type: flash.success ? 'success' : 'error',
            };
            setToasts((prev) => [...prev, newToast]);
        }
    }, [flash]);

    const removeToast = (id: string) => {
        setToasts((prev) => prev.filter((toast) => toast.id !== id));
    };

    const handleLogout = () => {
        router.post('/logout');
    };

    const closeSidebar = () => {
        setSidebarOpen(false);
    };

    const closeUserMenu = () => {
        setMenuOpen(false);
    };

    // Close sidebar when clicking outside (mobile only)
    useEffect(() => {
        if (!sidebarOpen) return;

        const handleClickOutside = (e: MouseEvent) => {
            const target = e.target as HTMLElement;
            if (target.closest('[data-sidebar]') || target.closest('[data-menu-button]')) {
                return;
            }
            setSidebarOpen(false);
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, [sidebarOpen]);

    // Close user menu when clicking outside
    useEffect(() => {
        if (!menuOpen) return;

        const handleClickOutside = (e: MouseEvent) => {
            const target = e.target as HTMLElement;
            if (target.closest('[data-user-menu]')) {
                return;
            }
            setMenuOpen(false);
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, [menuOpen]);

    const navItems = [
        { label: 'Dashboard', href: '/admin' },
        { label: 'Products', href: '/admin/products' },
        { label: 'Categories', href: '/admin/categories' },
        { label: 'Contacts', href: '/admin/contacts' },
        { label: 'Users', href: '/admin/users' },
    ];

    const isActive = (href: string) => {
        if (href === '/admin') {
            return url === href;
        }
        return url.startsWith(href);
    };

    return (
        <div className="flex h-screen bg-white">
            {/* Toast Container */}
            <ToastContainer toasts={toasts} onRemove={removeToast} />

            {/* Backdrop for mobile sidebar */}
            {sidebarOpen && (
                <div className="fixed inset-0 z-40 bg-black/30 backdrop-blur-[2px] lg:hidden" onClick={closeSidebar} aria-hidden="true" />
            )}

            {/* Sidebar */}
            <aside
                data-sidebar
                className={cn(
                    'fixed inset-y-0 left-0 z-50 w-64 transform border-r border-gray-200/80 bg-gray-50/95 backdrop-blur-xl transition-transform duration-300 ease-in-out lg:static lg:z-auto lg:translate-x-0',
                    sidebarOpen ? 'translate-x-0' : '-translate-x-full',
                )}
            >
                <div className="flex h-16 items-center justify-between border-b border-gray-200/60 px-5">
                    <h1 className="text-base font-medium text-gray-700">Admin</h1>
                    <button
                        onClick={closeSidebar}
                        className="rounded-lg p-2 text-gray-600 transition-colors hover:bg-gray-200/50 lg:hidden"
                        aria-label="Close sidebar"
                    >
                        <X size={20} />
                    </button>
                </div>

                <nav className="flex h-[calc(100vh-4rem)] flex-col">
                    <div className="flex-1 space-y-1 p-4">
                        {navItems.map((item) => (
                            <Link
                                key={item.href}
                                href={item.href}
                                className={cn(
                                    'block flex min-h-[44px] items-center rounded-md px-3 py-2 text-sm font-normal transition-colors duration-150',
                                    isActive(item.href) ? 'bg-gray-200/50 text-gray-900' : 'hover:bg-gray-150/40 text-gray-600 hover:text-gray-900',
                                )}
                                onClick={() => {
                                    // Close sidebar on mobile when clicking a link
                                    if (window.innerWidth < 1024) {
                                        closeSidebar();
                                    }
                                }}
                            >
                                {item.label}
                            </Link>
                        ))}
                    </div>

                    {/* Branding Footer */}
                    <div className="border-t border-gray-200/60 p-4">
                        <p className="text-xs text-gray-500">Built by Cangkir Co.</p>
                    </div>
                </nav>
            </aside>

            {/* Main Content */}
            <div className="flex flex-1 flex-col overflow-hidden">
                {/* Top Header */}
                <header className="shrink-0 border-b border-gray-200 bg-white shadow-sm">
                    <div className="flex h-16 items-center justify-between px-4 md:px-6">
                        <button
                            data-menu-button
                            onClick={() => setSidebarOpen(!sidebarOpen)}
                            className="flex min-h-[44px] min-w-[44px] items-center justify-center rounded-lg p-2 transition-colors hover:bg-gray-100 lg:hidden"
                            aria-label="Toggle sidebar"
                        >
                            <Menu size={24} />
                        </button>

                        <div className="flex-1" />

                        {/* User Menu */}
                        <div className="relative" data-user-menu>
                            <button
                                onClick={() => setMenuOpen(!menuOpen)}
                                className="min-h-[44px] rounded-lg px-4 py-2 text-sm font-normal text-gray-700 transition-colors hover:bg-gray-100/60"
                                aria-expanded={menuOpen}
                                aria-haspopup="true"
                            >
                                {auth.user?.name}
                            </button>
                            {menuOpen && (
                                <>
                                    {/* Backdrop for user menu */}
                                    <div className="fixed inset-0 z-10" onClick={closeUserMenu} aria-hidden="true" />
                                    <div className="absolute right-0 z-20 mt-2 w-48 rounded-lg border border-gray-200 bg-white shadow">
                                        <button
                                            onClick={handleLogout}
                                            className="flex min-h-[44px] w-full items-center gap-2 rounded-lg px-4 py-2.5 text-left font-normal text-red-600 transition-colors hover:bg-gray-50"
                                        >
                                            <LogOut size={16} />
                                            Logout
                                        </button>
                                    </div>
                                </>
                            )}
                        </div>
                    </div>
                </header>

                {/* Page Content */}
                <main className="flex-1 overflow-auto p-4 md:p-6">{children}</main>
            </div>
        </div>
    );
}
