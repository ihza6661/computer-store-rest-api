import { PageHeader } from '@/components/layout/PageHeader';
import { Button } from '@/components/ui/Button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card';
import { ConfirmModal } from '@/components/ui/ConfirmModal';
import { EmptyState } from '@/components/ui/EmptyState';
import { Input } from '@/components/ui/Input';
import { Select } from '@/components/ui/Select';
import { TableSkeleton } from '@/components/ui/Skeleton';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/Table';
import { apiDelete } from '@/lib/api';
import { formatPriceWithCurrency } from '@/lib/utils';
import { Head, Link } from '@inertiajs/react';
import { Edit2, FileSpreadsheet, Package, Plus, Search, Trash2, X } from 'lucide-react';
import { useEffect, useState } from 'react';
import AdminLayout from '../Layouts/AdminLayout';

interface Product {
    id: number;
    name: string;
    price: number;
    stock: number;
    image_url: string;
    category_id: number;
}

interface Category {
    id: number;
    name: string;
}

interface PaginationMeta {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

export default function ProductsIndex() {
    const [products, setProducts] = useState<Product[]>([]);
    const [loading, setLoading] = useState(true);

    // Pagination state
    const [pagination, setPagination] = useState<PaginationMeta>({
        current_page: 1,
        last_page: 1,
        per_page: 10,
        total: 0,
        from: 0,
        to: 0,
    });

    // Filter and search state
    const [searchQuery, setSearchQuery] = useState('');
    const [searchInput, setSearchInput] = useState('');
    const [categoryFilter, setCategoryFilter] = useState('');
    const [sortBy, setSortBy] = useState('created_at');
    const [sortOrder, setSortOrder] = useState('desc');

    // Categories for filter dropdown
    const [categories, setCategories] = useState<Category[]>([]);

    // Delete modal state
    const [deleteModalOpen, setDeleteModalOpen] = useState(false);
    const [productToDelete, setProductToDelete] = useState<number | null>(null);
    const [deleting, setDeleting] = useState(false);

    useEffect(() => {
        fetchCategories();
    }, []);

    useEffect(() => {
        fetchProducts(1);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [searchQuery, categoryFilter, sortBy, sortOrder]);

    const fetchCategories = async () => {
        try {
            const response = await fetch('/api/categories');
            const data = await response.json();
            setCategories(data);
        } catch (error) {
            console.error('Failed to fetch categories:', error);
        }
    };

    const fetchProducts = async (page: number = 1) => {
        setLoading(true);

        const params = new URLSearchParams({
            page: page.toString(),
            per_page: '10',
            sort_by: sortBy,
            order: sortOrder,
        });

        if (searchQuery) {
            params.append('search', searchQuery);
        }

        if (categoryFilter) {
            params.append('category_id', categoryFilter);
        }

        try {
            const response = await fetch(`/api/products?${params.toString()}`);
            const data = await response.json();

            setProducts(data.data || []);
            setPagination({
                current_page: data.current_page,
                last_page: data.last_page,
                per_page: data.per_page,
                total: data.total,
                from: data.from || 0,
                to: data.to || 0,
            });
        } catch (error) {
            console.error('Failed to fetch products:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        setSearchQuery(searchInput);
    };

    const clearSearch = () => {
        setSearchInput('');
        setSearchQuery('');
    };

    const handleDelete = async (id: number) => {
        setProductToDelete(id);
        setDeleteModalOpen(true);
    };

    const confirmDelete = async () => {
        if (!productToDelete) return;

        setDeleting(true);
        try {
            const response = await apiDelete(`/api/admin/products/${productToDelete}`);

            if (!response.ok) {
                alert('Failed to delete product');
                return;
            }

            // Smart page navigation: if last item on page, go to previous page
            const isLastItemOnPage = products.length === 1 && pagination.current_page > 1;
            const targetPage = isLastItemOnPage ? pagination.current_page - 1 : pagination.current_page;

            fetchProducts(targetPage);
            setDeleteModalOpen(false);
            setProductToDelete(null);
        } catch {
            alert('Error deleting product');
        } finally {
            setDeleting(false);
        }
    };

    const handlePageChange = (page: number) => {
        if (page >= 1 && page <= pagination.last_page) {
            fetchProducts(page);
        }
    };

    const getPageNumbers = () => {
        const pages = [];
        const { current_page, last_page } = pagination;

        // Always show first page
        pages.push(1);

        // Calculate range around current page
        const start = Math.max(2, current_page - 1);
        const end = Math.min(last_page - 1, current_page + 1);

        // Add ellipsis after first page if needed
        if (start > 2) {
            pages.push('...');
        }

        // Add pages around current
        for (let i = start; i <= end; i++) {
            pages.push(i);
        }

        // Add ellipsis before last page if needed
        if (end < last_page - 1) {
            pages.push('...');
        }

        // Always show last page if there's more than one page
        if (last_page > 1) {
            pages.push(last_page);
        }

        return pages;
    };

    return (
        <AdminLayout>
            <Head title="Products" />
            <div className="space-y-6">
                {/* Header with title and action button in separate flex container */}
                <div className="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <PageHeader title="Products" description="Manage your product inventory" />
                    <div className="flex gap-3">
                        <Link href="/admin/products/import">
                            <Button variant="secondary" size="md">
                                <FileSpreadsheet size={18} />
                                Import Products
                            </Button>
                        </Link>
                        <Link href="/admin/products/create">
                            <Button variant="primary" size="md">
                                <Plus size={18} />
                                Add Product
                            </Button>
                        </Link>
                    </div>
                </div>

                {/* Search and Filter Section - unwrapped from Card */}
                <div className="space-y-4">
                    <div className="grid grid-cols-1 gap-4 md:grid-cols-3">
                        {/* Search */}
                        <form onSubmit={handleSearch} className="relative">
                            <Input
                                type="text"
                                placeholder="Search products..."
                                value={searchInput}
                                onChange={(e) => setSearchInput(e.target.value)}
                                className="pr-10 pl-10"
                            />
                            <Search className="absolute top-1/2 left-3 -translate-y-1/2 transform text-gray-400" size={18} />
                            {searchInput && (
                                <button
                                    type="button"
                                    onClick={clearSearch}
                                    className="absolute top-1/2 right-3 -translate-y-1/2 transform p-1 text-gray-400 hover:text-gray-600"
                                >
                                    <X size={18} />
                                </button>
                            )}
                        </form>

                        {/* Category Filter */}
                        <Select value={categoryFilter} onChange={(e) => setCategoryFilter(e.target.value)}>
                            <option value="">All Categories</option>
                            {categories.map((category) => (
                                <option key={category.id} value={category.id}>
                                    {category.name}
                                </option>
                            ))}
                        </Select>

                        {/* Sort Options */}
                        <div className="flex gap-2">
                            <Select value={sortBy} onChange={(e) => setSortBy(e.target.value)} className="flex-1">
                                <option value="created_at">Date Added</option>
                                <option value="name">Name</option>
                                <option value="price">Price</option>
                                <option value="stock">Stock</option>
                            </Select>
                            <Select value={sortOrder} onChange={(e) => setSortOrder(e.target.value)}>
                                <option value="asc">Ascending</option>
                                <option value="desc">Descending</option>
                            </Select>
                        </div>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <div className="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                            <CardTitle>Product List</CardTitle>
                            {pagination.total > 0 && (
                                <p className="text-sm text-gray-600">
                                    Showing {pagination.from}-{pagination.to} of {pagination.total} products
                                </p>
                            )}
                        </div>
                    </CardHeader>
                    <CardContent>
                        {loading ? (
                            <TableSkeleton rows={5} />
                        ) : products.length === 0 ? (
                            <EmptyState
                                icon={<Package size={48} />}
                                title={searchQuery || categoryFilter ? 'No products found' : 'No products yet'}
                                description={
                                    searchQuery || categoryFilter
                                        ? 'Try adjusting your search or filter criteria.'
                                        : 'Create your first product to get started.'
                                }
                                action={
                                    !searchQuery && !categoryFilter ? (
                                        <Link href="/admin/products/create">
                                            <Button variant="primary">
                                                <Plus size={18} />
                                                Add Product
                                            </Button>
                                        </Link>
                                    ) : undefined
                                }
                            />
                        ) : (
                            <>
                                {/* Desktop/Tablet Table (≥768px) */}
                                <div className="hidden overflow-x-auto md:block">
                                    <Table>
                                        <TableHeader>
                                            <TableRow>
                                                <TableHead>Name</TableHead>
                                                <TableHead>Price</TableHead>
                                                <TableHead>Stock</TableHead>
                                                <TableHead className="text-right">Actions</TableHead>
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            {products.map((product) => (
                                                <TableRow key={product.id}>
                                                    <TableCell className="font-medium">{product.name}</TableCell>
                                                    <TableCell>{formatPriceWithCurrency(product.price)}</TableCell>
                                                    <TableCell>{product.stock} units</TableCell>
                                                    <TableCell className="text-right">
                                                        <div className="flex justify-end gap-2">
                                                            <Link href={`/admin/products/${product.id}/edit`}>
                                                                <Button variant="secondary" size="sm">
                                                                    <Edit2 size={16} />
                                                                    Edit
                                                                </Button>
                                                            </Link>
                                                            <Button variant="ghost" size="sm" onClick={() => handleDelete(product.id)}>
                                                                <Trash2 size={16} />
                                                                Delete
                                                            </Button>
                                                        </div>
                                                    </TableCell>
                                                </TableRow>
                                            ))}
                                        </TableBody>
                                    </Table>
                                </div>

                                {/* Mobile Card Layout (<768px) */}
                                <div className="space-y-3 md:hidden">
                                    {products.map((product) => (
                                        <Card key={product.id} className="border border-gray-200">
                                            <CardContent className="p-4">
                                                <div className="space-y-3">
                                                    <div>
                                                        <p className="font-semibold text-gray-900">{product.name}</p>
                                                        <div className="mt-2 space-y-1 text-sm">
                                                            <div className="flex justify-between">
                                                                <span className="text-gray-600">Price:</span>
                                                                <span className="font-medium text-gray-900">
                                                                    {formatPriceWithCurrency(product.price)}
                                                                </span>
                                                            </div>
                                                            <div className="flex justify-between">
                                                                <span className="text-gray-600">Stock:</span>
                                                                <span className="font-medium text-gray-900">{product.stock} units</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div className="flex gap-2 border-t border-gray-200 pt-2">
                                                        <Link href={`/admin/products/${product.id}/edit`} className="flex-1">
                                                            <Button variant="secondary" size="sm" className="w-full">
                                                                <Edit2 size={16} />
                                                                Edit
                                                            </Button>
                                                        </Link>
                                                        <Button variant="ghost" size="sm" onClick={() => handleDelete(product.id)} className="flex-1">
                                                            <Trash2 size={16} />
                                                            Delete
                                                        </Button>
                                                    </div>
                                                </div>
                                            </CardContent>
                                        </Card>
                                    ))}
                                </div>

                                {/* Pagination */}
                                {pagination.last_page > 1 && (
                                    <div className="mt-6 flex flex-col items-center justify-center gap-2 sm:flex-row">
                                        <Button
                                            variant="secondary"
                                            size="sm"
                                            onClick={() => handlePageChange(pagination.current_page - 1)}
                                            disabled={pagination.current_page === 1}
                                        >
                                            Previous
                                        </Button>

                                        <div className="flex flex-wrap justify-center gap-2">
                                            {getPageNumbers().map((page, index) =>
                                                page === '...' ? (
                                                    <span key={`ellipsis-${index}`} className="px-3 py-2 text-gray-500">
                                                        ...
                                                    </span>
                                                ) : (
                                                    <Button
                                                        key={page}
                                                        variant={pagination.current_page === page ? 'primary' : 'secondary'}
                                                        size="sm"
                                                        onClick={() => handlePageChange(page as number)}
                                                    >
                                                        {page}
                                                    </Button>
                                                ),
                                            )}
                                        </div>

                                        <Button
                                            variant="secondary"
                                            size="sm"
                                            onClick={() => handlePageChange(pagination.current_page + 1)}
                                            disabled={pagination.current_page === pagination.last_page}
                                        >
                                            Next
                                        </Button>
                                    </div>
                                )}
                            </>
                        )}
                    </CardContent>
                </Card>
            </div>

            {/* Delete Confirmation Modal */}
            <ConfirmModal
                open={deleteModalOpen}
                onClose={() => {
                    setDeleteModalOpen(false);
                    setProductToDelete(null);
                }}
                onConfirm={confirmDelete}
                title="Delete Product"
                description="Are you sure you want to delete this product? This action cannot be undone."
                confirmText="Delete"
                loading={deleting}
            />
        </AdminLayout>
    );
}
