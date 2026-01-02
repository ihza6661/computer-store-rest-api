import { PageHeader } from '@/components/layout/PageHeader';
import { Badge } from '@/components/ui/Badge';
import { Button } from '@/components/ui/Button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/Card';
import { Head, Link } from '@inertiajs/react';
import { Eye, FolderKanban, Mail, Package, Plus, Users } from 'lucide-react';
import AdminLayout from './Layouts/AdminLayout';

interface Contact {
    id: number;
    name: string;
    email: string;
    category: string;
    status: string;
    created_at: string;
}

interface Props {
    stats: {
        totalProducts: number;
        totalCategories: number;
        newContacts: number;
        totalUsers: number;
    };
    recentContacts: Contact[];
}

export default function Dashboard({ stats, recentContacts }: Props) {
    const statCards = [
        {
            title: 'Total Products',
            value: stats.totalProducts,
            description: 'Products in inventory',
            link: '/admin/products',
            icon: Package,
        },
        {
            title: 'Categories',
            value: stats.totalCategories,
            description: 'Product categories',
            link: '/admin/categories',
            icon: FolderKanban,
        },
        {
            title: 'New Contacts',
            value: stats.newContacts,
            description: 'Unread submissions',
            link: '/admin/contacts',
            icon: Mail,
        },
        {
            title: 'Users',
            value: stats.totalUsers,
            description: 'Admin users',
            link: '/admin/users',
            icon: Users,
        },
    ];

    return (
        <AdminLayout>
            <Head title="Dashboard" />
            <div className="space-y-6 md:space-y-8">
                <PageHeader title="Dashboard" description="Welcome to Computer Store Admin Panel" />

                {/* Stats Cards */}
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    {statCards.map((stat, index) => {
                        const Icon = stat.icon;
                        return (
                            <Link key={index} href={stat.link}>
                                <Card className="h-full cursor-pointer">
                                    <CardContent className="p-4 md:p-6">
                                        <div className="flex items-start justify-between">
                                            <div className="space-y-2">
                                                <p className="text-sm font-medium text-gray-600">{stat.title}</p>
                                                <p className="text-3xl font-bold text-gray-900">{stat.value}</p>
                                                <p className="text-xs text-gray-500">{stat.description}</p>
                                            </div>
                                            <div className="rounded-lg bg-gray-50 p-3">
                                                <Icon className="h-6 w-6 text-gray-400" />
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>
                            </Link>
                        );
                    })}
                </div>

                <div className="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    {/* Recent Contacts */}
                    <Card>
                        <CardHeader>
                            <div className="flex items-center justify-between">
                                <CardTitle>Recent Contacts</CardTitle>
                                <Link href="/admin/contacts">
                                    <Button variant="ghost" size="sm">
                                        View all →
                                    </Button>
                                </Link>
                            </div>
                            <CardDescription>Latest customer inquiries</CardDescription>
                        </CardHeader>
                        <CardContent>
                            {recentContacts.length === 0 ? (
                                <p className="py-8 text-center text-sm text-gray-500">No contact submissions yet.</p>
                            ) : (
                                <div className="space-y-3">
                                    {recentContacts.map((contact) => (
                                        <Link key={contact.id} href={`/admin/contacts/${contact.id}`}>
                                            <div className="flex cursor-pointer items-start justify-between rounded-lg border border-gray-200 p-3 transition-colors hover:bg-gray-50">
                                                <div className="min-w-0 flex-1">
                                                    <div className="mb-1 flex items-center gap-2">
                                                        <p className="truncate text-sm font-medium text-gray-900">{contact.name}</p>
                                                        <Badge>{contact.status}</Badge>
                                                    </div>
                                                    <p className="truncate text-xs text-gray-500">{contact.email}</p>
                                                    <p className="mt-1 text-xs text-gray-600 capitalize">{contact.category.replace('_', ' ')}</p>
                                                </div>
                                                <span className="ml-2 shrink-0 text-xs text-gray-400">
                                                    {new Date(contact.created_at).toLocaleDateString()}
                                                </span>
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            )}
                        </CardContent>
                    </Card>

                    {/* Quick Actions */}
                    <Card>
                        <CardHeader>
                            <CardTitle>Quick Actions</CardTitle>
                            <CardDescription>Common administrative tasks</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-2">
                                <Link href="/admin/products/create">
                                    <Button variant="ghost" className="h-auto w-full justify-start px-4 py-3">
                                        <div className="flex items-start gap-3 text-left">
                                            <Plus className="mt-0.5 h-5 w-5 shrink-0 text-gray-400" />
                                            <div>
                                                <p className="text-sm font-medium text-gray-900">Add New Product</p>
                                                <p className="mt-0.5 text-xs text-gray-500">Create a new product listing</p>
                                            </div>
                                        </div>
                                    </Button>
                                </Link>
                                <Link href="/admin/categories/create">
                                    <Button variant="ghost" className="h-auto w-full justify-start px-4 py-3">
                                        <div className="flex items-start gap-3 text-left">
                                            <Plus className="mt-0.5 h-5 w-5 shrink-0 text-gray-400" />
                                            <div>
                                                <p className="text-sm font-medium text-gray-900">Create Category</p>
                                                <p className="mt-0.5 text-xs text-gray-500">Add a new product category</p>
                                            </div>
                                        </div>
                                    </Button>
                                </Link>
                                <Link href="/admin/contacts">
                                    <Button variant="ghost" className="h-auto w-full justify-start px-4 py-3">
                                        <div className="flex items-start gap-3 text-left">
                                            <Eye className="mt-0.5 h-5 w-5 shrink-0 text-gray-400" />
                                            <div>
                                                <p className="text-sm font-medium text-gray-900">View Contacts</p>
                                                <p className="mt-0.5 text-xs text-gray-500">Manage customer inquiries</p>
                                            </div>
                                        </div>
                                    </Button>
                                </Link>
                                <Link href="/admin/users">
                                    <Button variant="ghost" className="h-auto w-full justify-start px-4 py-3">
                                        <div className="flex items-start gap-3 text-left">
                                            <Users className="mt-0.5 h-5 w-5 shrink-0 text-gray-400" />
                                            <div>
                                                <p className="text-sm font-medium text-gray-900">Manage Users</p>
                                                <p className="mt-0.5 text-xs text-gray-500">Add or edit admin users</p>
                                            </div>
                                        </div>
                                    </Button>
                                </Link>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AdminLayout>
    );
}
