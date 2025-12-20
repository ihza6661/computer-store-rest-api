import { Head, Link, useForm } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'

interface User {
  id: number
  name: string
  email: string
  role: string
  created_at: string
}

interface Props {
  user: User
}

export default function EditUser({ user }: Props) {
  const { data, setData, post, processing, errors } = useForm({
    name: user.name,
    email: user.email,
    role: user.role,
    password: '',
    _method: 'PUT' as const,
  })

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    post(`/admin/users/${user.id}`, {
      onSuccess: () => {
        // Inertia will handle the redirect
      },
    })
  }

  return (
    <AdminLayout>
      <Head title="Edit User" />
      <div className="space-y-6">
        <div className="flex items-center gap-4">
          <Link href="/admin/users">
            <button className="px-3 py-2 border border-gray-300 rounded hover:bg-gray-100">← Back</button>
          </Link>
          <div>
            <h1 className="text-3xl font-bold">Edit User</h1>
            <p className="text-gray-600">Update user information</p>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-lg font-bold mb-6">User Details</h2>

          {errors.general && (
            <div className="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
              {errors.general}
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm font-medium mb-1">Name *</label>
              <input
                type="text"
                value={data.name}
                onChange={(e) => setData({ ...data, name: e.currentTarget.value })}
                placeholder="Full name"
                className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              {errors.name && <p className="text-red-500 text-sm">{errors.name}</p>}
            </div>

            <div>
              <label className="block text-sm font-medium mb-1">Email *</label>
              <input
                type="email"
                value={data.email}
                onChange={(e) => setData({ ...data, email: e.currentTarget.value })}
                placeholder="user@example.com"
                className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              {errors.email && <p className="text-red-500 text-sm">{errors.email}</p>}
            </div>

            <div>
              <label className="block text-sm font-medium mb-1">Role *</label>
              <select
                value={data.role}
                onChange={(e) => setData({ ...data, role: e.currentTarget.value })}
                className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="admin">Admin</option>
                <option value="editor">Editor</option>
                <option value="viewer">Viewer</option>
              </select>
              {errors.role && <p className="text-red-500 text-sm">{errors.role}</p>}
            </div>

            <div>
              <label className="block text-sm font-medium mb-1">Password</label>
              <input
                type="password"
                value={data.password}
                onChange={(e) => setData({ ...data, password: e.currentTarget.value })}
                placeholder="Leave blank to keep current password"
                className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <p className="text-xs text-gray-500 mt-1">Leave blank if you don't want to change the password</p>
              {errors.password && <p className="text-red-500 text-sm">{errors.password}</p>}
            </div>

            <div className="flex gap-2">
              <button
                type="submit"
                disabled={processing}
                className="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50"
              >
                {processing ? 'Saving...' : 'Save Changes'}
              </button>
              <Link href="/admin/users">
                <button
                  type="button"
                  className="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors"
                >
                  Cancel
                </button>
              </Link>
            </div>
          </form>
        </div>
      </div>
    </AdminLayout>
  )
}
