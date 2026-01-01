import { PageHeader } from '@/components/layout/PageHeader';
import { Button } from '@/components/ui/Button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/Card';
import { ConfirmModal } from '@/components/ui/ConfirmModal';
import { EmptyState } from '@/components/ui/EmptyState';
import { CardSkeleton } from '@/components/ui/Skeleton';
import { apiDelete } from '@/lib/api';
import { Head, Link } from '@inertiajs/react';
import { FolderKanban, Plus } from 'lucide-react';
import { useEffect, useState } from 'react';
import AdminLayout from '../Layouts/AdminLayout';

interface Category {
    id: number;
    name: string;
    slug: string;
    description: string;
}

export default function CategoriesIndex() {
    const [categories, setCategories] = useState<Category[]>([]);
    const [loading, setLoading] = useState(true);
    const [deleteModalOpen, setDeleteModalOpen] = useState(false);
    const [categoryToDelete, setCategoryToDelete] = useState<number | null>(null);
    const [deleting, setDeleting] = useState(false);

    useEffect(() => {
        fetchCategories();
    }, []);

    const fetchCategories = () => {
        setLoading(true);
        fetch('/api/categories')
            .then((res) => res.json())
            .then((data) => {
                setCategories(data);
                setLoading(false);
            });
    };

    const handleDelete = (id: number) => {
        setCategoryToDelete(id);
        setDeleteModalOpen(true);
    };

    const confirmDelete = async () => {
        if (!categoryToDelete) return;

        setDeleting(true);
        try {
            const response = await apiDelete(`/api/admin/categories/${categoryToDelete}`);

            if (!response.ok) {
                alert('Failed to delete category');
                return;
            }

            fetchCategories();
            setDeleteModalOpen(false);
            setCategoryToDelete(null);
        } catch {
            alert('Error deleting category');
        } finally {
            setDeleting(false);
        }
    };

    return (
        <AdminLayout>
            <Head title="Categories" />
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <PageHeader title="Categories" description="Manage product categories" />
                    <Link href="/admin/categories/create">
                        <Button variant="primary" size="md">
                            <Plus size={18} />
                            Add Category
                        </Button>
                    </Link>
                </div>

                {loading ? (
                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        {[...Array(6)].map((_, i) => (
                            <CardSkeleton key={i} />
                        ))}
                    </div>
                ) : categories.length === 0 ? (
                    <Card>
                        <CardContent>
                            <EmptyState
                                icon={<FolderKanban size={48} />}
                                title="No categories yet"
                                description="Create your first product category to get started."
                                action={
                                    <Link href="/admin/categories/create">
                                        <Button variant="primary">
                                            <Plus size={18} />
                                            Add Category
                                        </Button>
                                    </Link>
                                }
                            />
                        </CardContent>
                    </Card>
                ) : (
                    <div className="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        {categories.map((cat) => (
                            <Card key={cat.id}>
                                <CardHeader>
                                    <CardTitle className="text-lg">{cat.name}</CardTitle>
                                    <CardDescription>{cat.description || 'No description'}</CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <div className="flex gap-2">
                                        <Link href={`/admin/categories/${cat.id}/edit`} className="flex-1">
                                            <Button variant="secondary" size="sm" className="w-full">
                                                Edit
                                            </Button>
                                        </Link>
                                        <Button variant="ghost" size="sm" onClick={() => handleDelete(cat.id)} className="flex-1">
                                            Delete
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                )}
            </div>

            {/* Delete Confirmation Modal */}
            <ConfirmModal
                open={deleteModalOpen}
                onClose={() => {
                    setDeleteModalOpen(false);
                    setCategoryToDelete(null);
                }}
                onConfirm={confirmDelete}
                title="Delete Category"
                description="Are you sure you want to delete this category? This action cannot be undone."
                confirmText="Delete"
                loading={deleting}
            />
        </AdminLayout>
    );
}
