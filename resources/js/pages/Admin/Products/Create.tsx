import { BackLink } from '@/components/ui/BackLink';
import { Button } from '@/components/ui/Button';
import { Head, Link, useForm } from '@inertiajs/react';
import React from 'react';
import AdminLayout from '../Layouts/AdminLayout';

interface Category {
    id: number;
    name: string;
}

interface Props {
    categories: Category[];
}

export default function CreateProduct({ categories }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        category_id: '',
        brand: '',
        price: '',
        sku: '',
        stock: '',
        description: '',
        images: [] as File[],
        specifications: {
            processor: '',
            gpu: '',
            ram: '',
            storage: '',
            display: '',
            keyboard: '',
            battery: '',
            warranty: '',
            condition: '',
            extras: '',
            original_price: '',
            features: '',
        },
    });

    const [imagePreviews, setImagePreviews] = React.useState<string[]>([]);
    const [sectionsOpen, setSectionsOpen] = React.useState({
        hardware: true,
        additional: true,
        pricing: true,
    });

    const toggleSection = (section: keyof typeof sectionsOpen) => {
        setSectionsOpen({ ...sectionsOpen, [section]: !sectionsOpen[section] });
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

        post('/admin/products', {
            forceFormData: true,
            onSuccess: () => {
                // Inertia will handle the redirect
            },
        });
    };

    const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const files = Array.from(e.currentTarget.files || []);

        if (files.length > 10) {
            alert('Maximum 10 images allowed');
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

    const removeImage = (index: number) => {
        const newImages = data.images.filter((_, i) => i !== index);
        const newPreviews = imagePreviews.filter((_, i) => i !== index);
        setData({ ...data, images: newImages });
        setImagePreviews(newPreviews);
    };

    return (
        <AdminLayout>
            <Head title="Create Product" />
            <div className="space-y-6">
                <div>
                    <BackLink href="/admin/products">Products</BackLink>
                    <h1 className="text-3xl font-bold">Create Product</h1>
                    <p className="text-gray-600">Add a new product to your inventory</p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow">
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

                            <div>
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
                                        {errors['specifications.processor'] && (
                                            <p className="text-sm text-red-500">{errors['specifications.processor']}</p>
                                        )}
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
                                                placeholder="e.g., 42Wh, 6 hours"
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
                                        {errors['specifications.condition'] && (
                                            <p className="text-sm text-red-500">{errors['specifications.condition']}</p>
                                        )}
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
                                            <span className="absolute top-1/2 left-3 -translate-y-1/2 font-medium text-gray-500">Rp</span>
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
                                            <span className="absolute top-1/2 left-3 -translate-y-1/2 font-medium text-gray-500">Rp</span>
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
                            <label className="mb-1 block text-sm font-medium">Images * (1-10 images)</label>
                            <input
                                type="file"
                                accept="image/*"
                                multiple
                                onChange={handleImageChange}
                                className="block w-full rounded-lg border px-3 py-2"
                            />
                            {errors.images && <p className="text-sm text-red-500">{errors.images}</p>}

                            {imagePreviews.length > 0 && (
                                <div className="mt-3">
                                    <p className="mb-2 text-sm text-gray-600">
                                        {imagePreviews.length} image(s) selected. First image will be the primary.
                                    </p>
                                    <div className="grid grid-cols-2 gap-3 md:grid-cols-4">
                                        {imagePreviews.map((preview, index) => (
                                            <div key={index} className="group relative">
                                                <img
                                                    src={preview}
                                                    alt={`Preview ${index + 1}`}
                                                    className="h-32 w-full rounded-lg border object-cover"
                                                />
                                                {index === 0 && (
                                                    <div className="absolute top-2 left-2 rounded bg-gray-800 px-2 py-1 text-xs text-white">
                                                        Primary
                                                    </div>
                                                )}
                                                <button
                                                    type="button"
                                                    onClick={() => removeImage(index)}
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

                        {/* Validation Warnings */}
                        {(!data.specifications.processor || !data.specifications.ram || !data.specifications.storage) && (
                            <div className="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <p className="mb-2 text-sm font-medium text-gray-700">Recommendation:</p>
                                <ul className="ml-4 list-disc space-y-1 text-sm text-gray-600">
                                    {!data.specifications.processor && <li>Add Processor for better product display</li>}
                                    {!data.specifications.ram && <li>Add RAM for better product display</li>}
                                    {!data.specifications.storage && <li>Add Storage for better product display</li>}
                                </ul>
                            </div>
                        )}

                        <div className="flex gap-2 border-t pt-4">
                            <Button type="submit" disabled={processing} variant="primary">
                                {processing ? 'Creating...' : 'Create Product'}
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
