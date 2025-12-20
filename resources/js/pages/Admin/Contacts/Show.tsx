import { Head, Link, useForm } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'
import { useState } from 'react'

interface Contact {
  id: number
  name: string
  email: string
  phone: string
  category: string
  message: string
  status: string
  admin_reply: string | null
  created_at: string
}

interface Props {
  contact: Contact
}

export default function ShowContact({ contact: initialContact }: Props) {
  const [contact, setContact] = useState<Contact>(initialContact)
  const [successMessage, setSuccessMessage] = useState('')
  
  const { data, setData, post, processing, errors, reset } = useForm({
    status: 'replied' as const,
    admin_reply: initialContact.admin_reply || '',
    _method: 'PUT' as const,
  })

  const handleReply = (e: React.FormEvent) => {
    e.preventDefault()
    setSuccessMessage('')
    
    post(`/admin/contacts/${contact.id}`, {
      onSuccess: (response: any) => {
        setSuccessMessage('Reply sent successfully!')
        // Update local contact state with the response
        if (response.props?.contact) {
          setContact(response.props.contact)
        }
      },
    })
  }

  return (
    <AdminLayout>
      <Head title="View Contact" />
      <div className="space-y-6">
        <div className="flex items-center gap-4">
          <Link href="/admin/contacts">
            <button className="px-3 py-2 border border-gray-300 rounded hover:bg-gray-100">← Back</button>
          </Link>
          <h1 className="text-3xl font-bold">Contact Submission</h1>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-lg font-bold mb-4">Submission Details</h2>
            <div className="space-y-4">
              <div>
                <label className="text-sm text-gray-600">Name</label>
                <p className="font-medium">{contact.name}</p>
              </div>
              <div>
                <label className="text-sm text-gray-600">Email</label>
                <p className="font-medium">{contact.email}</p>
              </div>
              <div>
                <label className="text-sm text-gray-600">Phone</label>
                <p className="font-medium">{contact.phone || 'N/A'}</p>
              </div>
              <div>
                <label className="text-sm text-gray-600">Category</label>
                <p className="font-medium capitalize">{contact.category.replace('_', ' ')}</p>
              </div>
              <div>
                <label className="text-sm text-gray-600">Status</label>
                <span className={`inline-block px-2 py-1 rounded text-xs mt-1 ${
                  contact.status === 'new' ? 'bg-blue-100 text-blue-800' :
                  contact.status === 'read' ? 'bg-yellow-100 text-yellow-800' :
                  'bg-green-100 text-green-800'
                }`}>
                  {contact.status}
                </span>
              </div>
              <div>
                <label className="text-sm text-gray-600">Submitted</label>
                <p className="font-medium">{new Date(contact.created_at).toLocaleString()}</p>
              </div>
            </div>
          </div>

          <div className="bg-white rounded-lg shadow p-6">
            <h2 className="text-lg font-bold mb-4">Message</h2>
            <p className="whitespace-pre-wrap">{contact.message}</p>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-lg font-bold mb-4">Admin Reply</h2>

          {successMessage && (
            <div className="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
              {successMessage}
            </div>
          )}

          {errors.general && (
            <div className="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
              {errors.general}
            </div>
          )}

          <form onSubmit={handleReply} className="space-y-4">
            <textarea
              value={data.admin_reply}
              onChange={(e) => setData('admin_reply', e.currentTarget.value)}
              placeholder="Send a reply to the customer..."
              className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              rows={6}
            />
            {errors.admin_reply && <p className="text-red-500 text-sm">{errors.admin_reply}</p>}
            <button
              type="submit"
              disabled={processing}
              className="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50"
            >
              {processing ? 'Sending...' : 'Send Reply'}
            </button>
          </form>
        </div>
      </div>
    </AdminLayout>
  )
}
