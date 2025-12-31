import { BackLink } from '@/components/ui/BackLink';
import { Button } from '@/components/ui/Button';
import { apiPost } from '@/lib/api';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../Layouts/AdminLayout';

interface FormErrors {
    [key: string]: string;
}

export default function CreateCategory() {
    const [data, setData] = useState({
        name: '',
        description: '',
    });

    const [errors, setErrors] = useState<FormErrors>({});
    const [loading, setLoading] = useState(false);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setErrors({});
        setLoading(true);

        try {
            const response = await apiPost('/api/admin/categories', data);

            if (!response.ok) {
                const errorData = await response.json();
                setErrors(errorData.errors || { general: 'An error occurred' });
                setLoading(false);
                return;
            }

            // Redirect to categories list
            router.visit('/admin/categories');
        } catch {
            setErrors({ general: 'Failed to create category' });
            setLoading(false);
        }
    };

    return (
        <AdminLayout>
            <Head title="Create Category" />
            <div className="space-y-6">
                <div>
                    <BackLink href="/admin/categories">Categories</BackLink>
                    <h1 className="text-3xl font-bold">Create Category</h1>
                    <p className="text-gray-600">Add a new product category</p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow">
                    <h2 className="mb-6 text-lg font-bold">Category Details</h2>

                    {errors.general && <div className="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700">{errors.general}</div>}

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <label className="mb-1 block text-sm font-medium">Name *</label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={(e) => setData({ ...data, name: e.currentTarget.value })}
                                placeholder="Category name"
                                className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                            />
                            {errors.name && <p className="text-sm text-red-500">{errors.name}</p>}
                        </div>

                        <div>
                            <label className="mb-1 block text-sm font-medium">Description</label>
                            <textarea
                                value={data.description}
                                onChange={(e) => setData({ ...data, description: e.currentTarget.value })}
                                placeholder="Category description"
                                className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                                rows={4}
                            />
                        </div>

                        <div className="flex gap-2">
                            <Button type="submit" disabled={loading} variant="primary">
                                {loading ? 'Creating...' : 'Create Category'}
                            </Button>
                            <Link href="/admin/categories">
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
