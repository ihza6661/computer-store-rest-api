import { Head } from '@inertiajs/react'
import AdminLayout from './Layouts/AdminLayout'

export default function Dashboard() {
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
            { title: 'Total Products', value: '0', desc: 'Products in inventory' },
            { title: 'Categories', value: '0', desc: 'Product categories' },
            { title: 'New Contacts', value: '0', desc: 'Unread submissions' },
            { title: 'Users', value: '0', desc: 'Admin users' },
          ].map((stat, i) => (
            <div key={i} className="bg-white rounded-lg shadow p-6">
              <h3 className="text-sm font-medium text-gray-600">{stat.title}</h3>
              <div className="text-2xl font-bold mt-2">{stat.value}</div>
              <p className="text-xs text-gray-500 mt-1">{stat.desc}</p>
            </div>
          ))}
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-lg font-bold mb-4">Getting Started</h2>
          <ul className="space-y-2 text-sm">
            <li>✓ Create product categories</li>
            <li>✓ Add products to your inventory</li>
            <li>✓ Manage contact submissions</li>
            <li>✓ Add more admin users</li>
          </ul>
        </div>
      </div>
    </AdminLayout>
  )
}
