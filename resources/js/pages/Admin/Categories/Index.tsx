import { Head, Link } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'
import { Plus } from 'lucide-react'
import { useEffect, useState } from 'react'

interface Category {
  id: number
  name: string
  slug: string
  description: string
}

export default function CategoriesIndex() {
  const [categories, setCategories] = useState<Category[]>([])
  const [loading, setLoading] = useState(true)

  useEffect(() => {
    fetchCategories()
  }, [])

  const fetchCategories = () => {
    fetch('/api/categories')
      .then((res) => res.json())
      .then((data) => {
        setCategories(data)
        setLoading(false)
      })
  }

  const handleDelete = async (id: number) => {
    if (!window.confirm('Are you sure you want to delete this category?')) {
      return
    }

    try {
      const response = await fetch(`/api/admin/categories/${id}`, {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
        },
      })

      if (!response.ok) {
        alert('Failed to delete category')
        return
      }

      // Refresh the list
      fetchCategories()
    } catch (error) {
      alert('Error deleting category')
    }
  }

  return (
    <AdminLayout>
      <Head title="Categories" />
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-3xl font-bold">Categories</h1>
            <p className="text-gray-600">Manage product categories</p>
          </div>
          <Link href="/admin/categories/create">
            <button className="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
              <Plus size={18} />
              Add Category
            </button>
          </Link>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-lg font-bold mb-4">Category List</h2>
          {loading ? (
            <p>Loading...</p>
          ) : categories.length === 0 ? (
            <p className="text-gray-500">No categories yet.</p>
          ) : (
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              {categories.map((cat) => (
                <div key={cat.id} className="border rounded-lg p-4">
                  <h3 className="text-lg font-bold">{cat.name}</h3>
                  <p className="text-sm text-gray-600">{cat.description}</p>
                   <div className="mt-4 space-x-2">
                     <Link href={`/admin/categories/${cat.id}/edit`}>
                       <button className="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100">Edit</button>
                     </Link>
                     <button
                       onClick={() => handleDelete(cat.id)}
                       className="px-3 py-1 border border-red-300 text-red-600 rounded hover:bg-red-50"
                     >
                       Delete
                     </button>
                   </div>
                </div>
              ))}
            </div>
          )}
        </div>
      </div>
    </AdminLayout>
  )
}
