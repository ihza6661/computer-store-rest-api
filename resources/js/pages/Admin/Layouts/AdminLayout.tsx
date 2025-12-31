import { PropsWithChildren, useEffect, useState } from 'react'
import { usePage, Link, router } from '@inertiajs/react'
import { LogOut, Menu, X } from 'lucide-react'
import { ToastContainer } from '@/components/ui/Toast'
import { cn } from '@/lib/utils'

interface PageProps {
    auth: {
        user?: {
            name: string
        }
    }
    flash?: {
        success?: string
        error?: string
    }
    [key: string]: unknown
}

interface Toast {
    id: string
    message: string
    type: 'success' | 'error'
}

export default function AdminLayout({ children }: PropsWithChildren) {
    const page = usePage<PageProps>()
    const { auth, flash } = page.props
    const url = page.url as string
    const [sidebarOpen, setSidebarOpen] = useState(false)
    const [menuOpen, setMenuOpen] = useState(false)
    const [toasts, setToasts] = useState<Toast[]>([])

    useEffect(() => {
        if (flash?.success || flash?.error) {
            const newToast: Toast = {
                id: Date.now().toString(),
                message: flash.success || flash.error || '',
                type: flash.success ? 'success' : 'error',
            }
            setToasts((prev) => [...prev, newToast])
        }
    }, [flash])

    const removeToast = (id: string) => {
        setToasts((prev) => prev.filter((toast) => toast.id !== id))
    }

    const handleLogout = () => {
        router.post('/logout')
    }

    const closeSidebar = () => {
        setSidebarOpen(false)
    }

    const closeUserMenu = () => {
        setMenuOpen(false)
    }

    // Close sidebar when clicking outside (mobile only)
    useEffect(() => {
        if (!sidebarOpen) return

        const handleClickOutside = (e: MouseEvent) => {
            const target = e.target as HTMLElement
            if (target.closest('[data-sidebar]') || target.closest('[data-menu-button]')) {
                return
            }
            setSidebarOpen(false)
        }

        document.addEventListener('mousedown', handleClickOutside)
        return () => document.removeEventListener('mousedown', handleClickOutside)
    }, [sidebarOpen])

    // Close user menu when clicking outside
    useEffect(() => {
        if (!menuOpen) return

        const handleClickOutside = (e: MouseEvent) => {
            const target = e.target as HTMLElement
            if (target.closest('[data-user-menu]')) {
                return
            }
            setMenuOpen(false)
        }

        document.addEventListener('mousedown', handleClickOutside)
        return () => document.removeEventListener('mousedown', handleClickOutside)
    }, [menuOpen])

    const navItems = [
        { label: 'Dashboard', href: '/admin' },
        { label: 'Products', href: '/admin/products' },
        { label: 'Categories', href: '/admin/categories' },
        { label: 'Contacts', href: '/admin/contacts' },
        { label: 'Users', href: '/admin/users' },
    ]

    const isActive = (href: string) => {
        if (href === '/admin') {
            return url === href
        }
        return url.startsWith(href)
    }

    return (
        <div className="flex h-screen bg-white">
            {/* Toast Container */}
            <ToastContainer toasts={toasts} onRemove={removeToast} />

            {/* Backdrop for mobile sidebar */}
            {sidebarOpen && (
                <div
                    className="fixed inset-0 z-40 bg-black/30 backdrop-blur-[2px] lg:hidden"
                    onClick={closeSidebar}
                    aria-hidden="true"
                />
            )}

            {/* Sidebar */}
            <aside
                data-sidebar
                className={cn(
                    'fixed inset-y-0 left-0 z-50 w-64 bg-gray-50/95 backdrop-blur-xl border-r border-gray-200/80 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto',
                    sidebarOpen ? 'translate-x-0' : '-translate-x-full'
                )}
            >
                <div className="flex items-center justify-between h-16 px-5 border-b border-gray-200/60">
                    <h1 className="text-base font-medium text-gray-700">Admin</h1>
                    <button
                        onClick={closeSidebar}
                        className="lg:hidden p-2 hover:bg-gray-200/50 rounded-lg transition-colors text-gray-600"
                        aria-label="Close sidebar"
                    >
                        <X size={20} />
                    </button>
                </div>

                <nav className="p-4 space-y-1">
                    {navItems.map((item) => (
                        <Link
                            key={item.href}
                            href={item.href}
                            className={cn(
                                'block px-3 py-2 rounded-md text-sm font-normal transition-colors duration-150 min-h-[44px] flex items-center',
                                isActive(item.href)
                                    ? 'bg-gray-200/50 text-gray-900'
                                    : 'text-gray-600 hover:bg-gray-150/40 hover:text-gray-900'
                            )}
                            onClick={() => {
                                // Close sidebar on mobile when clicking a link
                                if (window.innerWidth < 1024) {
                                    closeSidebar()
                                }
                            }}
                        >
                            {item.label}
                        </Link>
                    ))}
                </nav>
            </aside>

            {/* Main Content */}
            <div className="flex-1 flex flex-col overflow-hidden">
                {/* Top Header */}
                <header className="bg-white border-b border-gray-200 shadow-sm shrink-0">
                    <div className="flex items-center justify-between h-16 px-4 md:px-6">
                        <button
                            data-menu-button
                            onClick={() => setSidebarOpen(!sidebarOpen)}
                            className="lg:hidden p-2 hover:bg-gray-100 rounded-lg transition-colors min-h-[44px] min-w-[44px] flex items-center justify-center"
                            aria-label="Toggle sidebar"
                        >
                            <Menu size={24} />
                        </button>

                        <div className="flex-1" />

                        {/* User Menu */}
                        <div className="relative" data-user-menu>
                            <button
                                onClick={() => setMenuOpen(!menuOpen)}
                                className="px-4 py-2 hover:bg-gray-100/60 rounded-lg transition-colors text-sm font-normal text-gray-700 min-h-[44px]"
                                aria-expanded={menuOpen}
                                aria-haspopup="true"
                            >
                                {auth.user?.name}
                            </button>
                            {menuOpen && (
                                <>
                                    {/* Backdrop for user menu */}
                                    <div
                                        className="fixed inset-0 z-10"
                                        onClick={closeUserMenu}
                                        aria-hidden="true"
                                    />
                                    <div className="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow border border-gray-200 z-20">
                                        <button
                                            onClick={handleLogout}
                                            className="w-full text-left px-4 py-2.5 text-red-600 hover:bg-gray-50 flex items-center gap-2 rounded-lg font-normal transition-colors min-h-[44px]"
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
    )
}
