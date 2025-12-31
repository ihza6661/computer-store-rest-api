import { BackLink } from '@/components/ui/BackLink';
import { Button } from '@/components/ui/Button';
import { Head, Link, useForm } from '@inertiajs/react';
import AdminLayout from '../Layouts/AdminLayout';

interface Category {
    id: number;
    name: string;
    description: string;
}

interface Props {
    category: Category;
}

export default function EditCategory({ category }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        name: category.name,
        description: category.description || '',
        _method: 'PUT' as const,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(`/admin/categories/${category.id}`, {
            onSuccess: () => {
                // Inertia will handle the redirect
            },
        });
    };

    return (
        <AdminLayout>
            <Head title="Edit Category" />
            <div className="space-y-6">
                <div>
                    <BackLink href="/admin/categories">Categories</BackLink>
                    <h1 className="text-3xl font-bold">Edit Category</h1>
                    <p className="text-gray-600">Update category information</p>
                </div>

                <div className="rounded-lg bg-white p-6 shadow">
                    <h2 className="mb-6 text-lg font-bold">Category Details</h2>

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
                            <Button type="submit" disabled={processing} variant="primary">
                                {processing ? 'Saving...' : 'Save Changes'}
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
