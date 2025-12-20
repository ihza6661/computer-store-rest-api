import { Head, Link, usePage } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'
import { useEffect, useState } from 'react'

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

interface FormErrors {
  [key: string]: string
}

export default function ShowContact() {
  const { props } = usePage()
  const contactId = (props as any).contactId || 1

  const [contact, setContact] = useState<Contact | null>(null)
  const [replyText, setReplyText] = useState('')
  const [loading, setLoading] = useState(true)
  const [submitting, setSubmitting] = useState(false)
  const [errors, setErrors] = useState<FormErrors>({})

  useEffect(() => {
    fetch(`/api/admin/contacts/${contactId}`)
      .then((res) => res.json())
      .then((data: Contact) => {
        setContact(data)
        setReplyText(data.admin_reply || '')
        setLoading(false)
      })
  }, [contactId])

  const handleReply = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setSubmitting(true)

    try {
      const response = await fetch(`/api/admin/contacts/${contactId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          status: 'replied',
          admin_reply: replyText,
        }),
      })

      if (!response.ok) {
        const errorData = await response.json()
        setErrors(errorData.errors || { general: 'Failed to send reply' })
        setSubmitting(false)
        return
      }

      // Refresh the contact
      const updatedContact = await response.json()
      setContact(updatedContact)
      setReplyText(updatedContact.admin_reply || '')
      setSubmitting(false)
    } catch (error) {
      setErrors({ general: 'Error sending reply' })
      setSubmitting(false)
    }
  }

  if (loading) {
    return (
      <AdminLayout>
        <Head title="View Contact" />
        <div className="text-center py-8">
          <p className="text-gray-500">Loading contact...</p>
        </div>
      </AdminLayout>
    )
  }

  if (!contact) {
    return (
      <AdminLayout>
        <Head title="View Contact" />
        <div className="text-center py-8">
          <p className="text-gray-500">Contact not found</p>
        </div>
      </AdminLayout>
    )
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

          {errors.general && (
            <div className="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
              {errors.general}
            </div>
          )}

          <form onSubmit={handleReply} className="space-y-4">
            <textarea
              value={replyText}
              onChange={(e) => setReplyText(e.currentTarget.value)}
              placeholder="Send a reply to the customer..."
              className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              rows={6}
            />
            <button
              type="submit"
              disabled={submitting}
              className="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50"
            >
              {submitting ? 'Sending...' : 'Send Reply'}
            </button>
          </form>
        </div>
      </div>
    </AdminLayout>
  )
}
