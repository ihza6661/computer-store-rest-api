import { Head, Link } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'
import { useEffect, useState } from 'react'

interface Contact {
  id: number
  name: string
  email: string
  category: string
  status: string
  created_at: string
}

export default function ContactsIndex() {
  const [contacts, setContacts] = useState<Contact[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetch('/api/admin/contacts')
      .then((res) => res.json())
      .then((data) => {
        setContacts(data.data || [])
        setLoading(false)
      })
  }, [])

  return (
    <AdminLayout>
      <Head title="Contacts" />
      <div className="space-y-6">
        <div>
          <h1 className="text-3xl font-bold">Contact Submissions</h1>
          <p className="text-gray-600">Manage customer inquiries</p>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-lg font-bold mb-4">Submissions</h2>
          {loading ? (
            <p>Loading...</p>
          ) : contacts.length === 0 ? (
            <p className="text-gray-500">No contact submissions yet.</p>
          ) : (
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead className="bg-gray-50">
                  <tr>
                    <th className="px-4 py-2 text-left">Name</th>
                    <th className="px-4 py-2 text-left">Email</th>
                    <th className="px-4 py-2 text-left">Category</th>
                    <th className="px-4 py-2 text-left">Status</th>
                    <th className="px-4 py-2 text-left">Date</th>
                    <th className="px-4 py-2 text-left">Action</th>
                  </tr>
                </thead>
                <tbody>
                  {contacts.map((contact) => (
                    <tr key={contact.id} className="border-t">
                      <td className="px-4 py-3">{contact.name}</td>
                      <td className="px-4 py-3">{contact.email}</td>
                      <td className="px-4 py-3 capitalize">{contact.category.replace('_', ' ')}</td>
                      <td className="px-4 py-3">
                        <span className={`px-2 py-1 rounded text-xs ${
                          contact.status === 'new' ? 'bg-blue-100 text-blue-800' :
                          contact.status === 'read' ? 'bg-yellow-100 text-yellow-800' :
                          'bg-green-100 text-green-800'
                        }`}>
                          {contact.status}
                        </span>
                      </td>
                      <td className="px-4 py-3">{new Date(contact.created_at).toLocaleDateString()}</td>
                      <td className="px-4 py-3">
                        <Link href={`/admin/contacts/${contact.id}`}>
                          <button className="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">View</button>
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
