import { Head, Link } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'
import { useEffect, useState } from 'react'
import { Users as UsersIcon, Edit, UserCog } from 'lucide-react'
import { Button } from '@/components/ui/Button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/Table'
import { Badge } from '@/components/ui/Badge'
import { EmptyState } from '@/components/ui/EmptyState'
import { TableSkeleton } from '@/components/ui/Skeleton'
import { PageHeader } from '@/components/layout/PageHeader'

interface User {
  id: number
  name: string
  email: string
  role: string
  created_at: string
}

interface Props {
  users: {
    data: User[]
    total: number
    per_page: number
    current_page: number
    last_page: number
  }
}

export default function UsersIndex({ users: usersData }: Props) {
    const [users, setUsers] = useState<User[]>(usersData?.data || [])
    const [loading, setLoading] = useState(false)

    useEffect(() => {
        if (usersData?.data) {
            setUsers(usersData.data)
            setLoading(false)
        }
    }, [usersData])

    return (
        <AdminLayout>
            <Head title="Users" />
            <div className="space-y-6">
                <PageHeader title="Users" description="Manage admin users" />

                <Card>
                    <CardHeader>
                        <CardTitle>User List</CardTitle>
                    </CardHeader>
                    <CardContent>
                        {loading ? (
                            <TableSkeleton rows={5} />
                        ) : users.length === 0 ? (
                            <EmptyState
                                icon={<UsersIcon size={48} />}
                                title="No users found"
                                description="There are no admin users in the system."
                            />
                        ) : (
                            <>
                                {/* Desktop Table (≥768px) */}
                                <div className="hidden md:block overflow-x-auto">
                                    <Table>
                                        <TableHeader>
                                            <TableRow>
                                                <TableHead>Name</TableHead>
                                                <TableHead>Email</TableHead>
                                                <TableHead>Role</TableHead>
                                                <TableHead className="text-right">Action</TableHead>
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            {users.map((user) => (
                                                <TableRow key={user.id}>
                                                    <TableCell className="font-medium">{user.name}</TableCell>
                                                    <TableCell>{user.email}</TableCell>
                                                    <TableCell>
                                                        <Badge className="capitalize">
                                                            {user.role}
                                                        </Badge>
                                                    </TableCell>
                                                    <TableCell className="text-right">
                                                        <Link href={`/admin/users/${user.id}/edit`}>
                                                            <Button variant="primary" size="sm">
                                                                <Edit size={16} />
                                                                Edit
                                                            </Button>
                                                        </Link>
                                                    </TableCell>
                                                </TableRow>
                                            ))}
                                        </TableBody>
                                    </Table>
                                </div>

                                {/* Mobile Card Layout (<768px) */}
                                <div className="md:hidden space-y-3">
                                    {users.map((user) => (
                                        <Card key={user.id} className="border border-gray-200">
                                            <CardContent className="p-4">
                                                <div className="space-y-3">
                                                    <div>
                                                        <div className="flex items-start justify-between gap-2 mb-2">
                                                            <p className="font-semibold text-gray-900">{user.name}</p>
                                                            <Badge className="capitalize">
                                                                {user.role}
                                                            </Badge>
                                                        </div>
                                                        <p className="text-sm text-gray-600">{user.email}</p>
                                                    </div>
                                                    <Link href={`/admin/users/${user.id}/edit`}>
                                                        <Button variant="primary" size="sm" className="w-full">
                                                            <Edit size={16} />
                                                            Edit User
                                                        </Button>
                                                    </Link>
                                                </div>
                                            </CardContent>
                                        </Card>
                                    ))}
                                </div>
                            </>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AdminLayout>
    )
}
