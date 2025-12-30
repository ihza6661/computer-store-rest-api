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
  brand?: string
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
    brand: product.brand || '',
    price: String(product.price),
    sku: product.sku,
    stock: String(product.stock),
    description: product.description,
    images: [] as File[],
    specifications: {
      processor: product.specifications?.processor || '',
      gpu: product.specifications?.gpu || '',
      ram: product.specifications?.ram || '',
      storage: product.specifications?.storage || '',
      display: product.specifications?.display || '',
      keyboard: product.specifications?.keyboard || '',
      battery: product.specifications?.battery || '',
      warranty: product.specifications?.warranty || '',
      condition: product.specifications?.condition || '',
      extras: product.specifications?.extras || '',
      original_price: product.specifications?.original_price || '',
      features: product.specifications?.features || '',
    }
  })

  const [imagePreviews, setImagePreviews] = React.useState<string[]>([])
  const [deletingImageId, setDeletingImageId] = React.useState<number | null>(null)
  const [sectionsOpen, setSectionsOpen] = React.useState({
    hardware: true,
    additional: true,
    pricing: true,
  })

  const toggleSection = (section: keyof typeof sectionsOpen) => {
    setSectionsOpen({ ...sectionsOpen, [section]: !sectionsOpen[section] })
  }

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    
    // Validate original price if provided
    if (data.specifications.original_price && data.price) {
      const originalPrice = parseFloat(data.specifications.original_price)
      const currentPrice = parseFloat(data.price)
      if (originalPrice < currentPrice) {
        alert('Original price must be greater than or equal to current price')
        return
      }
    }

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

          <form onSubmit={handleSubmit} className="space-y-6">
            {/* Basic Information */}
            <div className="space-y-4">
              <h3 className="text-md font-semibold text-gray-800 border-b pb-2">Basic Information</h3>
              
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
                  <label className="block text-sm font-medium mb-1">Brand</label>
                  <input
                    type="text"
                    value={data.brand}
                    onChange={(e) => setData({ ...data, brand: e.currentTarget.value })}
                    placeholder="e.g., ASUS, Lenovo, Dell, HP"
                    className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  {errors.brand && <p className="text-red-500 text-sm">{errors.brand}</p>}
                </div>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium mb-1">SKU *</label>
                  <input
                    type="text"
                    value={data.sku}
                    onChange={(e) => setData({ ...data, sku: e.currentTarget.value })}
                    placeholder="Unique product code"
                    className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  {errors.sku && <p className="text-red-500 text-sm">{errors.sku}</p>}
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
            </div>

            {/* Hardware Specifications */}
            <div className="border rounded-lg overflow-hidden">
              <button
                type="button"
                onClick={() => toggleSection('hardware')}
                className="w-full bg-gray-50 px-4 py-3 flex items-center justify-between hover:bg-gray-100 transition-colors"
              >
                <h3 className="text-md font-semibold text-gray-800">
                  ⚙️ Hardware Specifications
                  <span className="text-xs text-gray-500 font-normal ml-2">(Recommended)</span>
                </h3>
                <span className="text-gray-500">{sectionsOpen.hardware ? '▼' : '▶'}</span>
              </button>
              
              {sectionsOpen.hardware && (
                <div className="p-4 space-y-4 bg-gray-50">
                  <div>
                    <label className="block text-sm font-medium mb-1">
                      Processor <span className="text-gray-500 text-xs">(⚙️ CPU - Recommended)</span>
                    </label>
                    <input
                      type="text"
                      value={data.specifications.processor}
                      onChange={(e) => setData({ ...data, specifications: { ...data.specifications, processor: e.currentTarget.value } })}
                      placeholder="e.g., Intel Core i5-8250U, AMD Ryzen 5 5500U"
                      className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium mb-1">
                      GPU <span className="text-gray-500 text-xs">(✨ Graphics Card)</span>
                    </label>
                    <input
                      type="text"
                      value={data.specifications.gpu}
                      onChange={(e) => setData({ ...data, specifications: { ...data.specifications, gpu: e.currentTarget.value } })}
                      placeholder="e.g., NVIDIA GTX 1050 2GB, Intel UHD Graphics"
                      className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>

                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-medium mb-1">
                        RAM <span className="text-gray-500 text-xs">(💾 Recommended)</span>
                      </label>
                      <input
                        type="text"
                        value={data.specifications.ram}
                        onChange={(e) => setData({ ...data, specifications: { ...data.specifications, ram: e.currentTarget.value } })}
                        placeholder="e.g., 8GB DDR4, 16GB"
                        className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                    </div>

                    <div>
                      <label className="block text-sm font-medium mb-1">
                        Storage <span className="text-gray-500 text-xs">(💿 Recommended)</span>
                      </label>
                      <input
                        type="text"
                        value={data.specifications.storage}
                        onChange={(e) => setData({ ...data, specifications: { ...data.specifications, storage: e.currentTarget.value } })}
                        placeholder="e.g., 256GB SSD NVMe, 512GB"
                        className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm font-medium mb-1">
                      Display <span className="text-gray-500 text-xs">(🖥️ Screen)</span>
                    </label>
                    <input
                      type="text"
                      value={data.specifications.display}
                      onChange={(e) => setData({ ...data, specifications: { ...data.specifications, display: e.currentTarget.value } })}
                      placeholder="e.g., 14 inch FHD IPS, 15.6 inch Full HD"
                      className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>

                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-medium mb-1">
                        Keyboard <span className="text-gray-500 text-xs">(Optional)</span>
                      </label>
                      <input
                        type="text"
                        value={data.specifications.keyboard}
                        onChange={(e) => setData({ ...data, specifications: { ...data.specifications, keyboard: e.currentTarget.value } })}
                        placeholder="e.g., Backlit Keyboard, RGB"
                        className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                    </div>

                    <div>
                      <label className="block text-sm font-medium mb-1">
                        Battery <span className="text-gray-500 text-xs">(Optional)</span>
                      </label>
                      <input
                        type="text"
                        value={data.specifications.battery}
                        onChange={(e) => setData({ ...data, specifications: { ...data.specifications, battery: e.currentTarget.value } })}
                        placeholder="e.g., 50Wh, 4-5 hours"
                        className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                    </div>
                  </div>
                </div>
              )}
            </div>

            {/* Additional Details */}
            <div className="border rounded-lg overflow-hidden">
              <button
                type="button"
                onClick={() => toggleSection('additional')}
                className="w-full bg-blue-50 px-4 py-3 flex items-center justify-between hover:bg-blue-100 transition-colors"
              >
                <h3 className="text-md font-semibold text-gray-800">
                  🎯 Additional Details
                </h3>
                <span className="text-gray-500">{sectionsOpen.additional ? '▼' : '▶'}</span>
              </button>
              
              {sectionsOpen.additional && (
                <div className="p-4 space-y-4 bg-blue-50">
                  <div>
                    <label className="block text-sm font-medium mb-1">
                      Condition * <span className="text-red-500">(Required)</span>
                    </label>
                    <select
                      value={data.specifications.condition}
                      onChange={(e) => setData({ ...data, specifications: { ...data.specifications, condition: e.currentTarget.value } })}
                      className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                      <option value="">Select condition</option>
                      <option value="excellent">Excellent (Sangat Baik)</option>
                      <option value="good">Good (Baik)</option>
                      <option value="fair">Fair (Cukup Baik)</option>
                    </select>
                    <p className="text-xs text-gray-600 mt-1">
                      This will be displayed as a badge on the product card
                    </p>
                  </div>

                  <div>
                    <label className="block text-sm font-medium mb-1">
                      Warranty <span className="text-gray-500 text-xs">(🛡️ Displayed in green)</span>
                    </label>
                    <input
                      type="text"
                      value={data.specifications.warranty}
                      onChange={(e) => setData({ ...data, specifications: { ...data.specifications, warranty: e.currentTarget.value } })}
                      placeholder="e.g., Garansi 1 Bulan Toko, Garansi 3 Bulan"
                      className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                    <p className="text-xs text-gray-600 mt-1">
                      Leave empty if no warranty
                    </p>
                  </div>

                  <div>
                    <label className="block text-sm font-medium mb-1">
                      Extras <span className="text-gray-500 text-xs">(🎁 Bonus items - Displayed in orange)</span>
                    </label>
                    <input
                      type="text"
                      value={data.specifications.extras}
                      onChange={(e) => setData({ ...data, specifications: { ...data.specifications, extras: e.currentTarget.value } })}
                      placeholder="e.g., Bonus Tas Laptop + Mouse Wireless"
                      className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium mb-1">
                      Features <span className="text-gray-500 text-xs">(Optional)</span>
                    </label>
                    <textarea
                      value={data.specifications.features}
                      onChange={(e) => setData({ ...data, specifications: { ...data.specifications, features: e.currentTarget.value } })}
                      placeholder="e.g., Fingerprint reader, USB-C port, etc."
                      className="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                      rows={3}
                    />
                  </div>
                </div>
              )}
            </div>

            {/* Pricing */}
            <div className="border rounded-lg overflow-hidden">
              <button
                type="button"
                onClick={() => toggleSection('pricing')}
                className="w-full bg-green-50 px-4 py-3 flex items-center justify-between hover:bg-green-100 transition-colors"
              >
                <h3 className="text-md font-semibold text-gray-800">
                  💰 Pricing & Discount
                </h3>
                <span className="text-gray-500">{sectionsOpen.pricing ? '▼' : '▶'}</span>
              </button>
              
              {sectionsOpen.pricing && (
                <div className="p-4 space-y-4 bg-green-50">
                  <div>
                    <label className="block text-sm font-medium mb-1">Current Price (Rp) *</label>
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
                      Selling price in Rupiah (e.g., 15000000 for Rp 15 million)
                    </p>
                    {errors.price && <p className="text-red-500 text-sm">{errors.price}</p>}
                  </div>

                  <div>
                    <label className="block text-sm font-medium mb-1">
                      Original Price (Rp) <span className="text-gray-500 text-xs">(Optional - For discount display)</span>
                    </label>
                    <div className="relative">
                      <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-medium">
                        Rp
                      </span>
                      <input
                        type="number"
                        step="1"
                        value={data.specifications.original_price}
                        onChange={(e) => setData({ ...data, specifications: { ...data.specifications, original_price: e.currentTarget.value } })}
                        placeholder="20000000"
                        className="w-full pl-12 pr-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                      />
                    </div>
                    <p className="text-xs text-gray-500 mt-1">
                      Leave empty if no discount. When filled, shows strikethrough price and savings amount.
                    </p>
                    {data.specifications.original_price && data.price && parseFloat(data.specifications.original_price) < parseFloat(data.price) && (
                      <p className="text-orange-600 text-sm mt-1">
                        ⚠️ Original price should be greater than current price
                      </p>
                    )}
                  </div>

                  {data.specifications.original_price && data.price && parseFloat(data.specifications.original_price) >= parseFloat(data.price) && (
                    <div className="bg-white rounded p-3 border border-green-200">
                      <p className="text-sm font-medium text-gray-700">Discount Preview:</p>
                      <p className="text-lg">
                        <span className="line-through text-gray-500">
                          Rp {parseFloat(data.specifications.original_price).toLocaleString('id-ID')}
                        </span>
                        {' → '}
                        <span className="text-green-600 font-bold">
                          Rp {parseFloat(data.price).toLocaleString('id-ID')}
                        </span>
                      </p>
                      <p className="text-sm text-green-600 font-semibold">
                        Save Rp {(parseFloat(data.specifications.original_price) - parseFloat(data.price)).toLocaleString('id-ID')}
                        {' '}
                        ({Math.round(((parseFloat(data.specifications.original_price) - parseFloat(data.price)) / parseFloat(data.specifications.original_price)) * 100)}% OFF)
                      </p>
                    </div>
                  )}
                </div>
              )}
            </div>

            {/* Images */}
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

            {/* Validation Warnings */}
            {(!data.specifications.processor || !data.specifications.ram || !data.specifications.storage) && (
              <div className="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p className="text-sm font-medium text-yellow-800 mb-2">⚠️ Recommendation:</p>
                <ul className="text-sm text-yellow-700 space-y-1 ml-4 list-disc">
                  {!data.specifications.processor && <li>Add Processor for better product display</li>}
                  {!data.specifications.ram && <li>Add RAM for better product display</li>}
                  {!data.specifications.storage && <li>Add Storage for better product display</li>}
                </ul>
              </div>
            )}

            <div className="flex gap-2 pt-4 border-t">
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
