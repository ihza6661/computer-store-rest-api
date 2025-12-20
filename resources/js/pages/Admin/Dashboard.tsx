import { Head, Link } from '@inertiajs/react'
import AdminLayout from './Layouts/AdminLayout'

interface Contact {
  id: number
  name: string
  email: string
  category: string
  status: string
  created_at: string
}

interface Props {
  stats: {
    totalProducts: number
    totalCategories: number
    newContacts: number
    totalUsers: number
  }
  recentContacts: Contact[]
}

export default function Dashboard({ stats, recentContacts }: Props) {
  return (
    <AdminLayout>
      <Head title="Dashboard" />
      <div className="space-y-6">
        <div>
          <h1 className="text-3xl font-bold">Dashboard</h1>
          <p className="text-gray-600">Welcome to R-Tech Computer Admin Panel</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          {[
            { title: 'Total Products', value: stats.totalProducts, desc: 'Products in inventory', link: '/admin/products' },
            { title: 'Categories', value: stats.totalCategories, desc: 'Product categories', link: '/admin/categories' },
            { title: 'New Contacts', value: stats.newContacts, desc: 'Unread submissions', link: '/admin/contacts' },
            { title: 'Users', value: stats.totalUsers, desc: 'Admin users', link: '/admin/users' },
          ].map((stat, i) => (
            <Link key={i} href={stat.link}>
              <div className="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow cursor-pointer">
                <h3 className="text-sm font-medium text-gray-600">{stat.title}</h3>
                <div className="text-3xl font-bold mt-2 text-blue-600">{stat.value}</div>
                <p className="text-xs text-gray-500 mt-1">{stat.desc}</p>
              </div>
            </Link>
          ))}
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div className="bg-white rounded-lg shadow p-6">
            <div className="flex items-center justify-between mb-4">
              <h2 className="text-lg font-bold">Recent Contacts</h2>
              <Link href="/admin/contacts">
                <span className="text-sm text-blue-600 hover:text-blue-700 cursor-pointer">View all →</span>
              </Link>
            </div>
            
            {recentContacts.length === 0 ? (
              <p className="text-gray-500 text-sm">No contact submissions yet.</p>
            ) : (
              <div className="space-y-3">
                {recentContacts.map((contact) => (
                  <Link key={contact.id} href={`/admin/contacts/${contact.id}`}>
                    <div className="flex items-start justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                      <div className="flex-1">
                        <div className="flex items-center gap-2">
                          <p className="font-medium text-sm">{contact.name}</p>
                          <span className={`px-2 py-0.5 rounded text-xs ${
                            contact.status === 'new' ? 'bg-blue-100 text-blue-800' :
                            contact.status === 'read' ? 'bg-yellow-100 text-yellow-800' :
                            'bg-green-100 text-green-800'
                          }`}>
                            {contact.status}
                          </span>
                        </div>
                        <p className="text-xs text-gray-500 mt-1">{contact.email}</p>
                        <p className="text-xs text-gray-600 mt-1 capitalize">
                          {contact.category.replace('_', ' ')}
                        </p>
                      </div>
                      <span className="text-xs text-gray-400">
                        {new Date(contact.created_at).toLocaleDateString()}
                      </span>
                    </div>
                  </Link>
                ))}
              </div>
            )}
          </div>

          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-lg font-bold mb-4">Quick Actions</h2>
            <div className="space-y-2">
              <Link href="/admin/products/create">
                <button className="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                  <span className="text-sm font-medium">➕ Add New Product</span>
                  <p className="text-xs text-gray-500 mt-1">Create a new product listing</p>
                </button>
              </Link>
              <Link href="/admin/categories/create">
                <button className="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                  <span className="text-sm font-medium">📁 Create Category</span>
                  <p className="text-xs text-gray-500 mt-1">Add a new product category</p>
                </button>
              </Link>
              <Link href="/admin/contacts">
                <button className="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                  <span className="text-sm font-medium">📧 View Contacts</span>
                  <p className="text-xs text-gray-500 mt-1">Manage customer inquiries</p>
                </button>
              </Link>
              <Link href="/admin/users">
                <button className="w-full text-left px-4 py-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                  <span className="text-sm font-medium">👥 Manage Users</span>
                  <p className="text-xs text-gray-500 mt-1">Add or edit admin users</p>
                </button>
              </Link>
            </div>
          </div>
        </div>
      </div>
    </AdminLayout>
  )
}
