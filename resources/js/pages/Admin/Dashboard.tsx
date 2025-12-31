import { Head, Link } from '@inertiajs/react'
import AdminLayout from './Layouts/AdminLayout'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/Card'
import { Badge } from '@/components/ui/Badge'
import { Button } from '@/components/ui/Button'
import { PageHeader } from '@/components/layout/PageHeader'
import { Package, FolderKanban, Mail, Users, Plus, Eye } from 'lucide-react'

interface Contact {
  id: number
  name: string
  email: string
  category: string
  status: string
  created_at: string
}

interface Props {
  stats: {
    totalProducts: number
    totalCategories: number
    newContacts: number
    totalUsers: number
  }
  recentContacts: Contact[]
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
    ]

    return (
        <AdminLayout>
            <Head title="Dashboard" />
            <div className="space-y-6 md:space-y-8">
                <PageHeader title="Dashboard" description="Welcome to Database Computer Admin Panel" />

                {/* Stats Cards */}
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {statCards.map((stat, index) => {
                        const Icon = stat.icon
                        return (
                            <Link key={index} href={stat.link}>
                                <Card className="cursor-pointer h-full">
                                    <CardContent className="p-4 md:p-6">
                                        <div className="flex items-start justify-between">
                                            <div className="space-y-2">
                                                <p className="text-sm font-medium text-gray-600">{stat.title}</p>
                                                <p className="text-3xl font-bold text-gray-900">{stat.value}</p>
                                                <p className="text-xs text-gray-500">{stat.description}</p>
                                            </div>
                                            <div className="p-3 rounded-lg bg-gray-50">
                                                <Icon className="h-6 w-6 text-gray-400" />
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>
                            </Link>
                        )
                    })}
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
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
                                <p className="text-sm text-gray-500 text-center py-8">No contact submissions yet.</p>
                            ) : (
                                <div className="space-y-3">
                                    {recentContacts.map((contact) => (
                                        <Link key={contact.id} href={`/admin/contacts/${contact.id}`}>
                                            <div className="flex items-start justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors cursor-pointer">
                                                <div className="flex-1 min-w-0">
                                                    <div className="flex items-center gap-2 mb-1">
                                                        <p className="font-medium text-sm text-gray-900 truncate">
                                                            {contact.name}
                                                        </p>
                                        <Badge>
                                            {contact.status}
                                        </Badge>
                                                    </div>
                                                    <p className="text-xs text-gray-500 truncate">{contact.email}</p>
                                                    <p className="text-xs text-gray-600 mt-1 capitalize">
                                                        {contact.category.replace('_', ' ')}
                                                    </p>
                                                </div>
                                                <span className="text-xs text-gray-400 ml-2 shrink-0">
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
                                    <Button variant="ghost" className="w-full justify-start h-auto py-3 px-4">
                                        <div className="flex items-start gap-3 text-left">
                                            <Plus className="h-5 w-5 text-gray-400 shrink-0 mt-0.5" />
                                            <div>
                                                <p className="text-sm font-medium text-gray-900">Add New Product</p>
                                                <p className="text-xs text-gray-500 mt-0.5">
                                                    Create a new product listing
                                                </p>
                                            </div>
                                        </div>
                                    </Button>
                                </Link>
                                <Link href="/admin/categories/create">
                                    <Button variant="ghost" className="w-full justify-start h-auto py-3 px-4">
                                        <div className="flex items-start gap-3 text-left">
                                            <Plus className="h-5 w-5 text-gray-400 shrink-0 mt-0.5" />
                                            <div>
                                                <p className="text-sm font-medium text-gray-900">Create Category</p>
                                                <p className="text-xs text-gray-500 mt-0.5">
                                                    Add a new product category
                                                </p>
                                            </div>
                                        </div>
                                    </Button>
                                </Link>
                                <Link href="/admin/contacts">
                                    <Button variant="ghost" className="w-full justify-start h-auto py-3 px-4">
                                        <div className="flex items-start gap-3 text-left">
                                            <Eye className="h-5 w-5 text-gray-400 shrink-0 mt-0.5" />
                                            <div>
                                                <p className="text-sm font-medium text-gray-900">View Contacts</p>
                                                <p className="text-xs text-gray-500 mt-0.5">
                                                    Manage customer inquiries
                                                </p>
                                            </div>
                                        </div>
                                    </Button>
                                </Link>
                                <Link href="/admin/users">
                                    <Button variant="ghost" className="w-full justify-start h-auto py-3 px-4">
                                        <div className="flex items-start gap-3 text-left">
                                            <Users className="h-5 w-5 text-gray-400 shrink-0 mt-0.5" />
                                            <div>
                                                <p className="text-sm font-medium text-gray-900">Manage Users</p>
                                                <p className="text-xs text-gray-500 mt-0.5">Add or edit admin users</p>
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
    )
}
