import { BackLink } from '@/components/ui/BackLink';
import { Button } from '@/components/ui/Button';
import { Head, Link, router, useForm } from '@inertiajs/react';
import React from 'react';
import AdminLayout from '../Layouts/AdminLayout';

interface Category {
    id: number;
    name: string;
}

interface ProductImage {
    id: number;
    image_url: string;
    image_thumbnail_url: string;
    is_primary: boolean;
    sort_order: number;
}

interface Product {
    id: number;
    name: string;
    brand?: string;
    category_id: number;
    price: string | number;
    sku: string;
    stock: number;
    description: string;
    image_url: string;
    images?: ProductImage[];
    specifications?: Record<string, string>;
}

interface Props {
    product: Product;
    categories: Category[];
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
        },
    });

    const [imagePreviews, setImagePreviews] = React.useState<string[]>([]);
    const [deletingImageId, setDeletingImageId] = React.useState<number | null>(null);
    const [uploadingImages, setUploadingImages] = React.useState(false);
    const [sectionsOpen, setSectionsOpen] = React.useState({
        hardware: true,
        additional: true,
        pricing: true,
    });
    const fileInputRef = React.useRef<HTMLInputElement>(null);

    const toggleSection = (section: keyof typeof sectionsOpen) => {
        setSectionsOpen({ ...sectionsOpen, [section]: !sectionsOpen[section] });
    };

    const clearFileInput = () => {
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
        setData({ ...data, images: [] });
        setImagePreviews([]);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        // Validate original price if provided
        if (data.specifications.original_price && data.price) {
            const originalPrice = parseFloat(data.specifications.original_price);
            const currentPrice = parseFloat(data.price);
            if (originalPrice < currentPrice) {
                alert('Original price must be greater than or equal to current price');
                return;
            }
        }

        post(`/admin/products/${product.id}`, {
            forceFormData: true,
            onStart: () => {
                if (data.images.length > 0) {
                    setUploadingImages(true);
                }
            },
            onSuccess: () => {
                clearFileInput();
                // Inertia will handle the redirect
            },
            onError: (errors) => {
                // If image upload fails, clear the input
                if (errors.images) {
                    clearFileInput();
                }
            },
            onFinish: () => {
                setUploadingImages(false);
            },
        });
    };

    const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const files = Array.from(e.currentTarget.files || []);

        const currentImageCount = product.images?.length || 0;
        const totalImages = currentImageCount + files.length;

        if (totalImages > 10) {
            alert(`Maximum 10 images allowed. You currently have ${currentImageCount} images.`);
            clearFileInput();
            return;
        }

        setData({ ...data, images: files });

        // Create preview URLs
        const previews: string[] = [];
        files.forEach((file) => {
            const reader = new FileReader();
            reader.onloadend = () => {
                previews.push(reader.result as string);
                if (previews.length === files.length) {
                    setImagePreviews(previews);
                }
            };
            reader.readAsDataURL(file);
        });
    };

    const removeNewImage = (index: number) => {
        const newImages = data.images.filter((_, i) => i !== index);
        const newPreviews = imagePreviews.filter((_, i) => i !== index);
        setData({ ...data, images: newImages });
        setImagePreviews(newPreviews);
    };

    const deleteExistingImage = (imageId: number) => {
        if (!confirm('Are you sure you want to delete this image?')) {
            return;
        }

        setDeletingImageId(imageId);
        router.delete(`/admin/products/${product.id}/images/${imageId}`, {
            preserveScroll: true,
            onSuccess: () => {
                setDeletingImageId(null);
            },
            onError: () => {
                setDeletingImageId(null);
                alert('Failed to delete image');
            },
        });
    };

    const setPrimaryImage = (imageId: number) => {
        router.post(
            `/admin/products/${product.id}/images/${imageId}/set-primary`,
            {},
            {
                preserveScroll: true,
                onError: () => {
                    alert('Failed to set primary image');
                },
            },
        );
    };

    return (
        <AdminLayout>
            <Head title="Edit Product" />
            <div className="space-y-6">
                <div>
                    <BackLink href="/admin/products">Products</BackLink>
                    <h1 className="text-3xl font-bold">Edit Product</h1>
                    <p className="text-sm text-gray-500">Update product information</p>
                </div>

                <div className="rounded-lg border border-gray-200/60 bg-white p-6">
                    <h2 className="mb-6 text-lg font-bold">Product Details</h2>

                    <form onSubmit={handleSubmit} className="space-y-6">
                        {/* Basic Information */}
                        <div className="space-y-4">
                            <h3 className="text-md border-b pb-2 font-semibold text-gray-800">Basic Information</h3>

                            <div>
                                <label className="mb-1 block text-sm font-medium">Name *</label>
                                <input
                                    type="text"
                                    value={data.name}
                                    onChange={(e) => setData({ ...data, name: e.currentTarget.value })}
                                    placeholder="Product name"
                                    className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                />
                                {errors.name && <p className="text-sm text-red-500">{errors.name}</p>}
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="mb-1 block text-sm font-medium">Category *</label>
                                    <select
                                        value={data.category_id}
                                        onChange={(e) => setData({ ...data, category_id: e.currentTarget.value })}
                                        className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                    >
                                        <option value="">Select category</option>
                                        {categories.map((cat) => (
                                            <option key={cat.id} value={cat.id}>
                                                {cat.name}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.category_id && <p className="text-sm text-red-500">{errors.category_id}</p>}
                                </div>

                                <div>
                                    <label className="mb-1 block text-sm font-medium">Brand</label>
                                    <input
                                        type="text"
                                        value={data.brand}
                                        onChange={(e) => setData({ ...data, brand: e.currentTarget.value })}
                                        placeholder="e.g., ASUS, Lenovo, Dell, HP"
                                        className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                    />
                                    {errors.brand && <p className="text-sm text-red-500">{errors.brand}</p>}
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="mb-1 block text-sm font-medium">SKU *</label>
                                    <input
                                        type="text"
                                        value={data.sku}
                                        onChange={(e) => setData({ ...data, sku: e.currentTarget.value })}
                                        placeholder="Unique product code"
                                        className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                    />
                                    {errors.sku && <p className="text-sm text-red-500">{errors.sku}</p>}
                                </div>

                                <div>
                                    <label className="mb-1 block text-sm font-medium">Stock *</label>
                                    <input
                                        type="number"
                                        value={data.stock}
                                        onChange={(e) => setData({ ...data, stock: e.currentTarget.value })}
                                        placeholder="0"
                                        className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                    />
                                    {errors.stock && <p className="text-sm text-red-500">{errors.stock}</p>}
                                </div>
                            </div>

                            <div className="mt-6">
                                <label className="mb-1 block text-sm font-medium">Description</label>
                                <textarea
                                    value={data.description}
                                    onChange={(e) => setData({ ...data, description: e.currentTarget.value })}
                                    placeholder="Product description"
                                    className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                    rows={4}
                                />
                            </div>
                        </div>

                        {/* Hardware Specifications */}
                        <div className="overflow-hidden rounded-lg border">
                            <button
                                type="button"
                                onClick={() => toggleSection('hardware')}
                                className="flex w-full items-center justify-between bg-gray-50 px-4 py-3 transition-colors hover:bg-gray-100/60"
                            >
                                <h3 className="text-md font-semibold text-gray-800">
                                    Hardware Specifications
                                    <span className="ml-2 text-xs font-normal text-gray-500">(Recommended)</span>
                                </h3>
                                <span className="text-gray-500">{sectionsOpen.hardware ? '▼' : '▶'}</span>
                            </button>

                            {sectionsOpen.hardware && (
                                <div className="space-y-4 bg-white p-4">
                                    <div>
                                        <label className="mb-1 block text-sm font-medium">
                                            Processor <span className="text-xs text-gray-500">(CPU - Recommended)</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.specifications.processor}
                                            onChange={(e) =>
                                                setData({ ...data, specifications: { ...data.specifications, processor: e.currentTarget.value } })
                                            }
                                            placeholder="e.g., Intel Core i5-8250U, AMD Ryzen 5 5500U"
                                            className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                        />
                                    </div>

                                    <div>
                                        <label className="mb-1 block text-sm font-medium">
                                            GPU <span className="text-xs text-gray-500">(Graphics Card)</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.specifications.gpu}
                                            onChange={(e) =>
                                                setData({ ...data, specifications: { ...data.specifications, gpu: e.currentTarget.value } })
                                            }
                                            placeholder="e.g., NVIDIA GTX 1050 2GB, Intel UHD Graphics"
                                            className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                        />
                                    </div>

                                    <div className="grid grid-cols-2 gap-4">
                                        <div>
                                            <label className="mb-1 block text-sm font-medium">
                                                RAM <span className="text-xs text-gray-500">(Recommended)</span>
                                            </label>
                                            <input
                                                type="text"
                                                value={data.specifications.ram}
                                                onChange={(e) =>
                                                    setData({ ...data, specifications: { ...data.specifications, ram: e.currentTarget.value } })
                                                }
                                                placeholder="e.g., 8GB DDR4, 16GB"
                                                className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                            />
                                        </div>

                                        <div>
                                            <label className="mb-1 block text-sm font-medium">
                                                Storage <span className="text-xs text-gray-500">(Recommended)</span>
                                            </label>
                                            <input
                                                type="text"
                                                value={data.specifications.storage}
                                                onChange={(e) =>
                                                    setData({ ...data, specifications: { ...data.specifications, storage: e.currentTarget.value } })
                                                }
                                                placeholder="e.g., 256GB SSD NVMe, 512GB"
                                                className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label className="mb-1 block text-sm font-medium">
                                            Display <span className="text-xs text-gray-500">(Screen)</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.specifications.display}
                                            onChange={(e) =>
                                                setData({ ...data, specifications: { ...data.specifications, display: e.currentTarget.value } })
                                            }
                                            placeholder="e.g., 14 inch FHD IPS, 15.6 inch Full HD"
                                            className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                        />
                                    </div>

                                    <div className="grid grid-cols-2 gap-4">
                                        <div>
                                            <label className="mb-1 block text-sm font-medium">
                                                Keyboard <span className="text-xs text-gray-500">(Optional)</span>
                                            </label>
                                            <input
                                                type="text"
                                                value={data.specifications.keyboard}
                                                onChange={(e) =>
                                                    setData({ ...data, specifications: { ...data.specifications, keyboard: e.currentTarget.value } })
                                                }
                                                placeholder="e.g., Backlit Keyboard, RGB"
                                                className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                            />
                                        </div>

                                        <div>
                                            <label className="mb-1 block text-sm font-medium">
                                                Battery <span className="text-xs text-gray-500">(Optional)</span>
                                            </label>
                                            <input
                                                type="text"
                                                value={data.specifications.battery}
                                                onChange={(e) =>
                                                    setData({ ...data, specifications: { ...data.specifications, battery: e.currentTarget.value } })
                                                }
                                                placeholder="e.g., 50Wh, 4-5 hours"
                                                className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                            />
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Additional Details */}
                        <div className="overflow-hidden rounded-lg border">
                            <button
                                type="button"
                                onClick={() => toggleSection('additional')}
                                className="flex w-full items-center justify-between bg-gray-50 px-4 py-3 transition-colors hover:bg-gray-100/60"
                            >
                                <h3 className="text-md font-semibold text-gray-800">Additional Details</h3>
                                <span className="text-gray-500">{sectionsOpen.additional ? '▼' : '▶'}</span>
                            </button>

                            {sectionsOpen.additional && (
                                <div className="space-y-4 bg-white p-4">
                                    <div>
                                        <label className="mb-1 block text-sm font-medium">
                                            Condition <span className="text-gray-400">*</span>
                                        </label>
                                        <select
                                            value={data.specifications.condition}
                                            onChange={(e) =>
                                                setData({ ...data, specifications: { ...data.specifications, condition: e.currentTarget.value } })
                                            }
                                            className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                        >
                                            <option value="">Select condition</option>
                                            <option value="new">New (Baru)</option>
                                            <option value="excellent">Excellent (Sangat Baik)</option>
                                            <option value="good">Good (Baik)</option>
                                            <option value="fair">Fair (Cukup Baik)</option>
                                            <option value="used-excellent">Used - Excellent (Bekas - Sangat Baik)</option>
                                            <option value="used-very-good">Used - Very Good (Bekas - Baik Sekali)</option>
                                            <option value="used-good">Used - Good (Bekas - Baik)</option>
                                        </select>
                                        <p className="mt-1 text-xs text-gray-600">This will be displayed as a badge on the product card</p>
                                    </div>

                                    <div>
                                        <label className="mb-1 block text-sm font-medium">
                                            Warranty <span className="text-xs text-gray-500">(Displayed as label)</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.specifications.warranty}
                                            onChange={(e) =>
                                                setData({ ...data, specifications: { ...data.specifications, warranty: e.currentTarget.value } })
                                            }
                                            placeholder="e.g., Garansi 1 Bulan Toko, Garansi 3 Bulan"
                                            className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                        />
                                        <p className="mt-1 text-xs text-gray-600">Leave empty if no warranty</p>
                                    </div>

                                    <div>
                                        <label className="mb-1 block text-sm font-medium">
                                            Extras <span className="text-xs text-gray-500">(Bonus items)</span>
                                        </label>
                                        <input
                                            type="text"
                                            value={data.specifications.extras}
                                            onChange={(e) =>
                                                setData({ ...data, specifications: { ...data.specifications, extras: e.currentTarget.value } })
                                            }
                                            placeholder="e.g., Bonus Tas Laptop + Mouse Wireless"
                                            className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                        />
                                    </div>

                                    <div>
                                        <label className="mb-1 block text-sm font-medium">
                                            Features <span className="text-xs text-gray-500">(Optional)</span>
                                        </label>
                                        <textarea
                                            value={data.specifications.features}
                                            onChange={(e) =>
                                                setData({ ...data, specifications: { ...data.specifications, features: e.currentTarget.value } })
                                            }
                                            placeholder="e.g., Fingerprint reader, USB-C port, etc."
                                            className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                            rows={3}
                                        />
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Pricing */}
                        <div className="overflow-hidden rounded-lg border">
                            <button
                                type="button"
                                onClick={() => toggleSection('pricing')}
                                className="flex w-full items-center justify-between bg-gray-50 px-4 py-3 transition-colors hover:bg-gray-100/60"
                            >
                                <h3 className="text-md font-semibold text-gray-800">Pricing & Discount</h3>
                                <span className="text-gray-500">{sectionsOpen.pricing ? '▼' : '▶'}</span>
                            </button>

                            {sectionsOpen.pricing && (
                                <div className="space-y-4 bg-white p-4">
                                    <div>
                                        <label className="mb-1 block text-sm font-medium">Current Price (Rp) *</label>
                                        <div className="relative">
                                            <span className="absolute top-1/2 left-3 -translate-y-1/2 font-normal text-gray-400">Rp</span>
                                            <input
                                                type="number"
                                                step="1"
                                                value={data.price}
                                                onChange={(e) => setData({ ...data, price: e.currentTarget.value })}
                                                placeholder="15000000"
                                                className="w-full rounded-lg border py-2 pr-3 pl-12 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                            />
                                        </div>
                                        <p className="mt-1 text-xs text-gray-500">Selling price in Rupiah (e.g., 15000000 for Rp 15 million)</p>
                                        {errors.price && <p className="text-sm text-red-500">{errors.price}</p>}
                                    </div>

                                    <div>
                                        <label className="mb-1 block text-sm font-medium">
                                            Original Price (Rp) <span className="text-xs text-gray-500">(Optional - For discount display)</span>
                                        </label>
                                        <div className="relative">
                                            <span className="absolute top-1/2 left-3 -translate-y-1/2 font-normal text-gray-400">Rp</span>
                                            <input
                                                type="number"
                                                step="1"
                                                value={data.specifications.original_price}
                                                onChange={(e) =>
                                                    setData({
                                                        ...data,
                                                        specifications: { ...data.specifications, original_price: e.currentTarget.value },
                                                    })
                                                }
                                                placeholder="20000000"
                                                className="w-full rounded-lg border py-2 pr-3 pl-12 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                            />
                                        </div>
                                        <p className="mt-1 text-xs text-gray-500">
                                            Leave empty if no discount. When filled, shows strikethrough price and savings amount.
                                        </p>
                                        {data.specifications.original_price &&
                                            data.price &&
                                            parseFloat(data.specifications.original_price) < parseFloat(data.price) && (
                                                <p className="mt-1 text-sm text-gray-600">Original price should be greater than current price</p>
                                            )}
                                    </div>

                                    {data.specifications.original_price &&
                                        data.price &&
                                        parseFloat(data.specifications.original_price) >= parseFloat(data.price) && (
                                            <div className="rounded border border-gray-200 bg-white p-3">
                                                <p className="text-sm font-medium text-gray-700">Discount Preview:</p>
                                                <p className="text-lg">
                                                    <span className="text-gray-500 line-through">
                                                        Rp {parseFloat(data.specifications.original_price).toLocaleString('id-ID')}
                                                    </span>
                                                    {' → '}
                                                    <span className="font-semibold text-gray-700">
                                                        Rp {parseFloat(data.price).toLocaleString('id-ID')}
                                                    </span>
                                                </p>
                                                <p className="text-sm font-medium text-gray-600">
                                                    Save Rp{' '}
                                                    {(parseFloat(data.specifications.original_price) - parseFloat(data.price)).toLocaleString(
                                                        'id-ID',
                                                    )}{' '}
                                                    (
                                                    {Math.round(
                                                        ((parseFloat(data.specifications.original_price) - parseFloat(data.price)) /
                                                            parseFloat(data.specifications.original_price)) *
                                                            100,
                                                    )}
                                                    % OFF)
                                                </p>
                                            </div>
                                        )}
                                </div>
                            )}
                        </div>

                        {/* Images */}
                        <div>
                            <label className="mb-1 block text-sm font-medium">Images</label>

                            {/* Existing Images */}
                            {product.images && product.images.length > 0 && (
                                <div className="mb-4">
                                    <p className="mb-2 text-sm text-gray-600">Existing Images ({product.images.length}/10):</p>
                                    <div className="grid grid-cols-2 gap-3 md:grid-cols-4">
                                        {product.images.map((img) => (
                                            <div key={img.id} className="group relative">
                                                <img src={img.image_url} alt={product.name} className="h-32 w-full rounded-lg border object-cover" />
                                                {img.is_primary && (
                                                    <div className="absolute top-2 left-2 flex items-center gap-1 rounded bg-gray-800 px-2 py-1 text-xs text-white">
                                                        Primary
                                                    </div>
                                                )}
                                                <div className="absolute top-2 right-2 flex gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                                    {!img.is_primary && (
                                                        <Button
                                                            variant="primary"
                                                            size="sm"
                                                            onClick={() => setPrimaryImage(img.id)}
                                                            className="text-xs"
                                                        >
                                                            Set Primary
                                                        </Button>
                                                    )}
                                                    {product.images!.length > 1 && (
                                                        <button
                                                            type="button"
                                                            onClick={() => deleteExistingImage(img.id)}
                                                            disabled={deletingImageId === img.id}
                                                            className="flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-white hover:bg-red-700 disabled:opacity-50"
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
                                <label className="mb-2 block text-sm text-gray-700">
                                    Add New Images {product.images && `(${product.images.length + data.images.length}/10)`}
                                </label>
                                <input
                                    ref={fileInputRef}
                                    type="file"
                                    accept="image/*"
                                    multiple
                                    onChange={handleImageChange}
                                    className="block w-full rounded-lg border px-3 py-2"
                                    disabled={(product.images?.length || 0) >= 10}
                                />
                                {errors.images && (
                                    <div className="mt-2 rounded-lg border border-red-200 bg-red-50 p-3">
                                        <p className="text-sm font-medium text-red-600">Image Upload Error:</p>
                                        <p className="text-sm text-red-600">{errors.images}</p>
                                        <button
                                            type="button"
                                            onClick={clearFileInput}
                                            className="mt-1 text-sm text-red-600 underline hover:text-red-800"
                                        >
                                            Clear and try again
                                        </button>
                                    </div>
                                )}

                                {imagePreviews.length > 0 && (
                                    <div className="mt-3">
                                        <p className="mb-2 text-sm text-gray-600">New images to add ({imagePreviews.length}):</p>
                                        <div className="grid grid-cols-2 gap-3 md:grid-cols-4">
                                            {imagePreviews.map((preview, index) => (
                                                <div key={index} className="group relative">
                                                    <img
                                                        src={preview}
                                                        alt={`New ${index + 1}`}
                                                        className="h-32 w-full rounded-lg border object-cover"
                                                    />
                                                    <button
                                                        type="button"
                                                        onClick={() => removeNewImage(index)}
                                                        className="absolute top-2 right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-600 text-white opacity-0 transition-opacity group-hover:opacity-100"
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
                            <div className="space-y-1 text-sm text-gray-600">
                                {!data.specifications.processor && <p>Consider adding Processor for better product display</p>}
                                {!data.specifications.ram && <p>Consider adding RAM for better product display</p>}
                                {!data.specifications.storage && <p>Consider adding Storage for better product display</p>}
                            </div>
                        )}

                        <div className="flex justify-end gap-2 border-t pt-4">
                            <Button type="submit" disabled={processing || uploadingImages} variant="primary">
                                {uploadingImages ? `Uploading ${data.images.length} image(s)...` : processing ? 'Saving...' : 'Save Changes'}
                            </Button>
                            <Link href="/admin/products">
                                <Button type="button" variant="secondary">
                                    Cancel
                                </Button>
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </AdminLayout>
    );
}
