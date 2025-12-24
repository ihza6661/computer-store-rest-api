import { PropsWithChildren, useEffect } from 'react'
import { usePage, Link, router } from '@inertiajs/react'
import { LogOut, Menu, X } from 'lucide-react'
import { useState } from 'react'

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
}

export default function AdminLayout({ children }: PropsWithChildren) {
  const { auth, flash } = usePage<PageProps>().props
  const [sidebarOpen, setSidebarOpen] = useState(false)
  const [menuOpen, setMenuOpen] = useState(false)
  const [showFlash, setShowFlash] = useState(false)

  useEffect(() => {
    if (flash?.success || flash?.error) {
      setShowFlash(true)
      const timer = setTimeout(() => setShowFlash(false), 5000)
      return () => clearTimeout(timer)
    }
  }, [flash])

  const handleLogout = () => {
    router.post('/logout')
  }

  const navItems = [
    { label: 'Dashboard', href: '/admin' },
    { label: 'Products', href: '/admin/products' },
    { label: 'Categories', href: '/admin/categories' },
    { label: 'Contacts', href: '/admin/contacts' },
    { label: 'Users', href: '/admin/users' },
  ]

  return (
    <div className="flex h-screen bg-gray-100">
      {/* Sidebar */}
      <div
        className={`${
          sidebarOpen ? 'translate-x-0' : '-translate-x-full'
        } fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-white transform transition-transform duration-200 lg:translate-x-0 lg:static`}
      >
        <div className="flex items-center justify-between h-16 px-4 border-b border-slate-700">
          <h1 className="text-xl font-bold">Database</h1>
          <button
            onClick={() => setSidebarOpen(false)}
            className="lg:hidden"
          >
            <X size={24} />
          </button>
        </div>

        <nav className="p-4 space-y-2">
          {navItems.map((item) => (
            <Link
              key={item.href}
              href={item.href}
              className="block px-4 py-2 rounded-lg hover:bg-slate-800 transition-colors"
            >
              {item.label}
            </Link>
          ))}
        </nav>
      </div>

      {/* Main Content */}
      <div className="flex-1 flex flex-col overflow-hidden">
        {/* Top Header */}
        <header className="bg-white border-b border-gray-200 shadow-sm">
          <div className="flex items-center justify-between h-16 px-4">
            <button
              onClick={() => setSidebarOpen(!sidebarOpen)}
              className="lg:hidden p-2"
            >
              <Menu size={24} />
            </button>

            <div className="flex-1" />

            {/* User Menu */}
            <div className="relative">
              <button
                onClick={() => setMenuOpen(!menuOpen)}
                className="px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors"
              >
                {auth.user?.name}
              </button>
              {menuOpen && (
                <div className="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50">
                  <button
                    onClick={handleLogout}
                    className="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 flex items-center gap-2 rounded-lg"
                  >
                    <LogOut size={16} />
                    Logout
                  </button>
                </div>
              )}
            </div>
          </div>
        </header>

        {/* Flash Messages */}
        {showFlash && (flash?.success || flash?.error) && (
          <div className="px-6 pt-4">
            {flash.success && (
              <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex justify-between items-center">
                <span>{flash.success}</span>
                <button
                  onClick={() => setShowFlash(false)}
                  className="text-green-700 font-bold text-xl leading-none hover:text-green-900"
                >
                  ×
                </button>
              </div>
            )}
            {flash.error && (
              <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative flex justify-between items-center">
                <span>{flash.error}</span>
                <button
                  onClick={() => setShowFlash(false)}
                  className="text-red-700 font-bold text-xl leading-none hover:text-red-900"
                >
                  ×
                </button>
              </div>
            )}
          </div>
        )}

        {/* Page Content */}
        <main className="flex-1 overflow-auto p-6">
          {children}
        </main>
      </div>
    </div>
  )
}
