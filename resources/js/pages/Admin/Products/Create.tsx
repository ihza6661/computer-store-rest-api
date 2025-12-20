import { Head, Link, router } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'
import { useEffect, useState } from 'react'

interface Category {
  id: number
  name: string
}

interface FormData {
  name: string
  category_id: string
  price: string
  sku: string
  stock: string
  description: string
  image: File | null
  specifications: Record<string, string>
}

interface FormErrors {
  [key: string]: string
}

export default function CreateProduct() {
  const [data, setData] = useState<FormData>({
    name: '',
    category_id: '',
    price: '',
    sku: '',
    stock: '',
    description: '',
    image: null,
    specifications: {},
  })

  const [categories, setCategories] = useState<Category[]>([])
  const [errors, setErrors] = useState<FormErrors>({})
  const [loading, setLoading] = useState(false)

  useEffect(() => {
    fetch('/api/categories')
      .then((res) => res.json())
      .then((data) => setCategories(data))
  }, [])

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault()
    setErrors({})
    setLoading(true)

    const formData = new FormData()
    formData.append('name', data.name)
    formData.append('category_id', data.category_id)
    formData.append('price', data.price)
    formData.append('sku', data.sku)
    formData.append('stock', data.stock)
    formData.append('description', data.description)
    if (data.image) {
      formData.append('image', data.image)
    }
    if (Object.keys(data.specifications).length > 0) {
      formData.append('specifications', JSON.stringify(data.specifications))
    }

    try {
      const response = await fetch('/api/admin/products', {
        method: 'POST',
        body: formData,
        headers: {
          'Accept': 'application/json',
        },
      })

      if (!response.ok) {
        const errorData = await response.json()
        setErrors(errorData.errors || { general: 'An error occurred' })
        setLoading(false)
        return
      }

      // Redirect to products list
      router.visit('/admin/products')
    } catch (error) {
      setErrors({ general: 'Failed to create product' })
      setLoading(false)
    }
  }

  return (
    <AdminLayout>
      <Head title="Create Product" />
      <div className="space-y-6">
        <div className="flex items-center gap-4">
          <Link href="/admin/products">
            <button className="px-3 py-2 border border-gray-300 rounded hover:bg-gray-100">← Back</button>
          </Link>
          <div>
            <h1 className="text-3xl font-bold">Create Product</h1>
            <p className="text-gray-600">Add a new product to your inventory</p>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-lg font-bold mb-6">Product Details</h2>

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
                placeholder="Product name"
                className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              {errors.name && <p className="text-red-500 text-sm">{errors.name}</p>}
            </div>

            <div className="grid grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-medium mb-1">Category *</label>
                <select
                  value={data.category_id}
                  onChange={(e) => setData({ ...data, category_id: e.currentTarget.value })}
                  className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="">Select category</option>
                  {categories.map((cat) => (
                    <option key={cat.id} value={cat.id}>
                      {cat.name}
                    </option>
                  ))}
                </select>
                {errors.category_id && <p className="text-red-500 text-sm">{errors.category_id}</p>}
              </div>

              <div>
                <label className="block text-sm font-medium mb-1">SKU *</label>
                <input
                  type="text"
                  value={data.sku}
                  onChange={(e) => setData({ ...data, sku: e.currentTarget.value })}
                  placeholder="SKU"
                  className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                {errors.sku && <p className="text-red-500 text-sm">{errors.sku}</p>}
              </div>
            </div>

            <div className="grid grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-medium mb-1">Price *</label>
                <input
                  type="number"
                  step="0.01"
                  value={data.price}
                  onChange={(e) => setData({ ...data, price: e.currentTarget.value })}
                  placeholder="0.00"
                  className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                {errors.price && <p className="text-red-500 text-sm">{errors.price}</p>}
              </div>

              <div>
                <label className="block text-sm font-medium mb-1">Stock *</label>
                <input
                  type="number"
                  value={data.stock}
                  onChange={(e) => setData({ ...data, stock: e.currentTarget.value })}
                  placeholder="0"
                  className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                {errors.stock && <p className="text-red-500 text-sm">{errors.stock}</p>}
              </div>
            </div>

            <div>
              <label className="block text-sm font-medium mb-1">Description</label>
              <textarea
                value={data.description}
                onChange={(e) => setData({ ...data, description: e.currentTarget.value })}
                placeholder="Product description"
                className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                rows={4}
              />
            </div>

            <div>
              <label className="block text-sm font-medium mb-1">Image *</label>
              <input
                type="file"
                accept="image/*"
                onChange={(e) => {
                  const file = e.currentTarget.files?.[0]
                  if (file) setData({ ...data, image: file })
                }}
                className="block w-full px-3 py-2 border rounded-lg"
              />
              {errors.image && <p className="text-red-500 text-sm">{errors.image}</p>}
            </div>

            <div className="flex gap-2">
              <button
                type="submit"
                disabled={loading}
                className="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50"
              >
                {loading ? 'Creating...' : 'Create Product'}
              </button>
              <Link href="/admin/products">
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
