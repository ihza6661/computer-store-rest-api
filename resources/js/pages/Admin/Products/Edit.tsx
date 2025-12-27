import { Head, Link, useForm, router } from '@inertiajs/react'
import React from 'react'
import AdminLayout from '../Layouts/AdminLayout'

interface Category {
  id: number
  name: string
}

interface ProductImage {
  id: number
  image_url: string
  image_thumbnail_url: string
  is_primary: boolean
  sort_order: number
}

interface Product {
  id: number
  name: string
  category_id: number
  price: string | number
  sku: string
  stock: number
  description: string
  image_url: string
  images?: ProductImage[]
  specifications?: Record<string, string>
}

interface Props {
  product: Product
  categories: Category[]
}

export default function EditProduct({ product, categories }: Props) {
  const { data, setData, post, processing, errors } = useForm({
    name: product.name,
    category_id: String(product.category_id),
    price: String(product.price),
    sku: product.sku,
    stock: String(product.stock),
    description: product.description,
    images: [] as File[],
  })

  const [imagePreviews, setImagePreviews] = React.useState<string[]>([])
  const [deletingImageId, setDeletingImageId] = React.useState<number | null>(null)

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    post(`/admin/products/${product.id}`, {
      forceFormData: true,
      onSuccess: () => {
        // Inertia will handle the redirect
      },
    })
  }

  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const files = Array.from(e.currentTarget.files || [])
    
    const currentImageCount = product.images?.length || 0
    const totalImages = currentImageCount + files.length
    
    if (totalImages > 10) {
      alert(`Maximum 10 images allowed. You currently have ${currentImageCount} images.`)
      return
    }
    
    setData({ ...data, images: files })
    
    // Create preview URLs
    const previews: string[] = []
    files.forEach((file) => {
      const reader = new FileReader()
      reader.onloadend = () => {
        previews.push(reader.result as string)
        if (previews.length === files.length) {
          setImagePreviews(previews)
        }
      }
      reader.readAsDataURL(file)
    })
  }

  const removeNewImage = (index: number) => {
    const newImages = data.images.filter((_, i) => i !== index)
    const newPreviews = imagePreviews.filter((_, i) => i !== index)
    setData({ ...data, images: newImages })
    setImagePreviews(newPreviews)
  }

  const deleteExistingImage = (imageId: number) => {
    if (!confirm('Are you sure you want to delete this image?')) {
      return
    }
    
    setDeletingImageId(imageId)
    router.delete(`/admin/products/${product.id}/images/${imageId}`, {
      preserveScroll: true,
      onSuccess: () => {
        setDeletingImageId(null)
      },
      onError: () => {
        setDeletingImageId(null)
        alert('Failed to delete image')
      },
    })
  }

  const setPrimaryImage = (imageId: number) => {
    router.post(`/admin/products/${product.id}/images/${imageId}/set-primary`, {}, {
      preserveScroll: true,
      onError: () => {
        alert('Failed to set primary image')
      },
    })
  }

  return (
    <AdminLayout>
      <Head title="Edit Product" />
      <div className="space-y-6">
        <div className="flex items-center gap-4">
          <Link href="/admin/products">
            <button className="px-3 py-2 border border-gray-300 rounded hover:bg-gray-100">← Back</button>
          </Link>
          <div>
            <h1 className="text-3xl font-bold">Edit Product</h1>
            <p className="text-gray-600">Update product information</p>
          </div>
        </div>

        <div className="bg-white rounded-lg shadow p-6">
          <h2 className="text-lg font-bold mb-6">Product Details</h2>

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
                <label className="block text-sm font-medium mb-1">Price (Rp) *</label>
                <div className="relative">
                  <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">
                    Rp
                  </span>
                  <input
                    type="number"
                    step="1"
                    value={data.price}
                    onChange={(e) => setData({ ...data, price: e.currentTarget.value })}
                    placeholder="15000000"
                    className="w-full pl-12 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                </div>
                <p className="text-xs text-gray-500 mt-1">
                  Masukkan harga dalam Rupiah (contoh: 15000000 untuk Rp 15 juta)
                </p>
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
              <label className="block text-sm font-medium mb-1">Images</label>
              
              {/* Existing Images */}
              {product.images && product.images.length > 0 && (
                <div className="mb-4">
                  <p className="text-sm text-gray-600 mb-2">
                    Existing Images ({product.images.length}/10):
                  </p>
                  <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                    {product.images.map((img) => (
                      <div key={img.id} className="relative group">
                        <img
                          src={img.image_url}
                          alt={product.name}
                          className="w-full h-32 object-cover border rounded-lg"
                        />
                        {img.is_primary && (
                          <div className="absolute top-2 left-2 bg-blue-600 text-white text-xs px-2 py-1 rounded flex items-center gap-1">
                            ★ Primary
                          </div>
                        )}
                        <div className="absolute top-2 right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                          {!img.is_primary && (
                            <button
                              type="button"
                              onClick={() => setPrimaryImage(img.id)}
                              className="bg-blue-600 text-white rounded px-2 py-1 text-xs hover:bg-blue-700"
                              title="Set as primary"
                            >
                              ★
                            </button>
                          )}
                          {product.images!.length > 1 && (
                            <button
                              type="button"
                              onClick={() => deleteExistingImage(img.id)}
                              disabled={deletingImageId === img.id}
                              className="bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-700 disabled:opacity-50"
                              title="Delete image"
                            >
                              {deletingImageId === img.id ? '...' : '×'}
                            </button>
                          )}
                        </div>
                      </div>
                    ))}
                  </div>
                </div>
              )}
              
              {/* Add New Images */}
              <div>
                <label className="block text-sm text-gray-700 mb-2">
                  Add New Images {product.images && `(${product.images.length + data.images.length}/10)`}
                </label>
                <input
                  type="file"
                  accept="image/*"
                  multiple
                  onChange={handleImageChange}
                  className="block w-full px-3 py-2 border rounded-lg"
                  disabled={(product.images?.length || 0) >= 10}
                />
                {errors.images && <p className="text-red-500 text-sm">{errors.images}</p>}
                
                {imagePreviews.length > 0 && (
                  <div className="mt-3">
                    <p className="text-sm text-gray-600 mb-2">
                      New images to add ({imagePreviews.length}):
                    </p>
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
                      {imagePreviews.map((preview, index) => (
                        <div key={index} className="relative group">
                          <img
                            src={preview}
                            alt={`New ${index + 1}`}
                            className="w-full h-32 object-cover border rounded-lg"
                          />
                          <button
                            type="button"
                            onClick={() => removeNewImage(index)}
                            className="absolute top-2 right-2 bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                          >
                            ×
                          </button>
                        </div>
                      ))}
                    </div>
                  </div>
                )}
              </div>
            </div>

            <div className="flex gap-2">
              <button
                type="submit"
                disabled={processing}
                className="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors disabled:opacity-50"
              >
                {processing ? 'Saving...' : 'Save Changes'}
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
