import { Head, Link } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'

export default function EditUser() {
  const userId = new URLSearchParams(window.location.search).get('userId') || '1'

  return (
    <AdminLayout>
      <Head title="Edit User" />
      <div className="space-y-6">
        <div className="flex items-center gap-4">
          <Link href="/admin/users">
            <button className="px-3 py-2 border border-gray-300 rounded hover:bg-gray-100">← Back</button>
          </Link>
          <h1 className="text-3xl font-bold">Edit User</h1>
        </div>
        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-lg font-bold">User #{userId}</h2>
          <p className="text-gray-600 mt-2">Edit user form here</p>
        </div>
      </div>
    </AdminLayout>
  )
}
