import { Head, Link, router } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'
import { Plus, Edit2, Trash2 } from 'lucide-react'
import { useEffect, useState } from 'react'

interface Product {
  id: number
  name: string
  price: number
  stock: number
  image_url: string
  category_id: number
}

export default function ProductsIndex() {
  const [products, setProducts] = useState<Product[]>([])
  const [loading, setLoading] = useState(true)
  const [deleteId, setDeleteId] = useState<number | null>(null)

  useEffect(() => {
    fetchProducts()
  }, [])

  const fetchProducts = () => {
    fetch('/api/products')
      .then((res) => res.json())
      .then((data) => {
        setProducts(data.data || [])
        setLoading(false)
      })
  }

  const handleDelete = async (id: number) => {
    if (!window.confirm('Are you sure you want to delete this product?')) {
      return
    }

    try {
      const response = await fetch(`/api/admin/products/${id}`, {
        method: 'DELETE',
        headers: {
          'Accept': 'application/json',
        },
      })

      if (!response.ok) {
        alert('Failed to delete product')
        return
      }

      // Refresh the list
      fetchProducts()
    } catch (error) {
      alert('Error deleting product')
    }
  }

  return (
    <AdminLayout>
      <Head title="Products" />
      <div className="space-y-6">
        <div className="flex justify-between items-center">
          <div>
            <h1 className="text-3xl font-bold">Products</h1>
            <p className="text-gray-600">Manage your product inventory</p>
          </div>
          <Link href="/admin/products/create">
            <button className="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
              <Plus size={18} />
              Add Product
            </button>
          </Link>
        </div>

        <div className="bg-white rounded-lg shadow">
          <div className="p-6">
            <h2 className="text-lg font-bold mb-4">Product List</h2>
            {loading ? (
              <p className="text-gray-500">Loading products...</p>
            ) : products.length === 0 ? (
              <p className="text-gray-500">No products yet. Create your first product!</p>
            ) : (
              <div className="overflow-x-auto">
                <table className="w-full">
                  <thead className="bg-gray-50">
                    <tr>
                      <th className="px-4 py-2 text-left">Name</th>
                      <th className="px-4 py-2 text-left">Price</th>
                      <th className="px-4 py-2 text-left">Stock</th>
                      <th className="px-4 py-2 text-left">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    {products.map((product) => (
                      <tr key={product.id} className="border-t">
                        <td className="px-4 py-3">{product.name}</td>
                        <td className="px-4 py-3">Rp {Number(product.price).toLocaleString('id-ID', { maximumFractionDigits: 0 })}</td>
                        <td className="px-4 py-3">{product.stock} units</td>
                        <td className="px-4 py-3 space-x-2">
                          <Link href={`/admin/products/${product.id}/edit`}>
                            <button className="px-3 py-1 border border-gray-300 rounded hover:bg-gray-100 inline-flex items-center gap-1">
                              <Edit2 size={16} />
                            </button>
                          </Link>
                          <button
                            onClick={() => handleDelete(product.id)}
                            className="px-3 py-1 border border-red-300 text-red-600 rounded hover:bg-red-50 inline-flex items-center gap-1"
                          >
                            <Trash2 size={16} />
                          </button>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            )}
          </div>
        </div>
      </div>
    </AdminLayout>
  )
}
