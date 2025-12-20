import { Head, Link, useForm } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'

interface Category {
  id: number
  name: string
  description: string
}

interface Props {
  category: Category
}

export default function EditCategory({ category }: Props) {
  const { data, setData, post, processing, errors } = useForm({
    name: category.name,
    description: category.description || '',
    _method: 'PUT' as const,
  })

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    post(`/admin/categories/${category.id}`, {
      onSuccess: () => {
        // Inertia will handle the redirect
      },
    })
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
                disabled={processing}
                className="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50"
              >
                {processing ? 'Saving...' : 'Save Changes'}
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
