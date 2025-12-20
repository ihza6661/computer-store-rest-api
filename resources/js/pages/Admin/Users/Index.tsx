import { Head, Link } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'
import { useEffect, useState } from 'react'

interface User {
  id: number
  name: string
  email: string
  role: string
}

export default function UsersIndex() {
  const [users, setUsers] = useState<User[]>([])

  useEffect(() => {
    fetch('/api/users')
      .then((res) => res.json())
      .then((data) => setUsers(data.data || []))
      .catch(() => setUsers([]))
  }, [])

  return (
    <AdminLayout>
      <Head title="Users" />
      <div className="space-y-6">
        <div>
          <h1 className="text-3xl font-bold">Users</h1>
          <p className="text-gray-600">Manage admin users</p>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-lg font-bold mb-4">User List</h2>
          {users.length === 0 ? (
            <p className="text-gray-500">No users found.</p>
          ) : (
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-4 py-2 text-left">Name</th>
                    <th className="px-4 py-2 text-left">Email</th>
                    <th className="px-4 py-2 text-left">Role</th>
                    <th className="px-4 py-2 text-left">Action</th>
                  </tr>
                </thead>
                <tbody>
                  {users.map((user) => (
                    <tr key={user.id} className="border-t">
                      <td className="px-4 py-3">{user.name}</td>
                      <td className="px-4 py-3">{user.email}</td>
                      <td className="px-4 py-3 capitalize">{user.role}</td>
                      <td className="px-4 py-3">
                        <Link href={`/admin/users/${user.id}/edit`}>
                          <button className="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Edit</button>
                        </Link>
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </div>
      </div>
    </AdminLayout>
  )
}
