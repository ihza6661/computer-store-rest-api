import { Head, Link, router, usePage } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'
import { useEffect, useState } from 'react'

interface Category {
  id: number
  name: string
  description: string
}

interface FormErrors {
  [key: string]: string
}

export default function EditCategory() {
  const { params } = usePage()
  const categoryId = params?.id

  const [data, setData] = useState({
    name: '',
    description: '',
  })

  const [errors, setErrors] = useState<FormErrors>({})
  const [loading, setLoading] = useState(true)
  const [submitting, setSubmitting] = useState(false)

  useEffect(() => {
    if (categoryId) {
      fetch(`/api/categories/${categoryId}`)
        .then((res) => res.json())
        .then((category: Category) => {
          setData({
            name: category.name,
            description: category.description,
          })
          setLoading(false)
        })
        .catch(() => {
          setLoading(false)
        })
    }
  }, [categoryId])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setSubmitting(true)

    try {
      const response = await fetch(`/api/admin/categories/${categoryId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(data),
      })

      if (!response.ok) {
        const errorData = await response.json()
        setErrors(errorData.errors || { general: 'An error occurred' })
        setSubmitting(false)
        return
      }

      // Redirect to categories list
      router.visit('/admin/categories')
    } catch (error) {
      setErrors({ general: 'Failed to update category' })
      setSubmitting(false)
    }
  }

  if (loading) {
    return (
      <AdminLayout>
        <Head title="Edit Category" />
        <div className="text-center py-8">
          <p className="text-gray-500">Loading category...</p>
        </div>
      </AdminLayout>
    )
  }

  return (
    <AdminLayout>
      <Head title="Edit Category" />
      <div className="space-y-6">
        <div className="flex items-center gap-4">
          <Link href="/admin/categories">
            <button className="px-3 py-2 border border-gray-300 rounded hover:bg-gray-100">← Back</button>
          </Link>
          <div>
            <h1 className="text-3xl font-bold">Edit Category</h1>
            <p className="text-gray-600">Update category information</p>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-lg font-bold mb-6">Category Details</h2>

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
                placeholder="Category name"
                className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              {errors.name && <p className="text-red-500 text-sm">{errors.name}</p>}
            </div>

            <div>
              <label className="block text-sm font-medium mb-1">Description</label>
              <textarea
                value={data.description}
                onChange={(e) => setData({ ...data, description: e.currentTarget.value })}
                placeholder="Category description"
                className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                rows={4}
              />
            </div>

            <div className="flex gap-2">
              <button
                type="submit"
                disabled={submitting}
                className="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50"
              >
                {submitting ? 'Saving...' : 'Save Changes'}
              </button>
              <Link href="/admin/categories">
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
