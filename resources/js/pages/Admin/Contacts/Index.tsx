import { Head, Link } from '@inertiajs/react'
import AdminLayout from '../Layouts/AdminLayout'
import { Mail, Eye } from 'lucide-react'
import { Button } from '@/components/ui/Button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/Card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/Table'
import { Badge } from '@/components/ui/Badge'
import { EmptyState } from '@/components/ui/EmptyState'
import { PageHeader } from '@/components/layout/PageHeader'

interface Contact {
  id: number
  name: string
  email: string
  category: string
  status: string
  created_at: string
}

interface Props {
  contacts: {
    data: Contact[]
    total: number
    per_page: number
    current_page: number
  }
}

export default function ContactsIndex({ contacts }: Props) {
    const contactList = contacts?.data || []

    return (
        <AdminLayout>
            <Head title="Contacts" />
            <div className="space-y-6">
                <PageHeader title="Contact Submissions" description="Manage customer inquiries" />

                <Card>
                    <CardHeader>
                        <CardTitle>Submissions</CardTitle>
                    </CardHeader>
                    <CardContent>
                        {contactList.length === 0 ? (
                            <EmptyState
                                icon={<Mail size={48} />}
                                title="No contact submissions yet"
                                description="Customer inquiries will appear here when submitted."
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
                                                <TableHead>Category</TableHead>
                                                <TableHead>Status</TableHead>
                                                <TableHead>Date</TableHead>
                                                <TableHead className="text-right">Action</TableHead>
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            {contactList.map((contact) => (
                                                <TableRow key={contact.id}>
                                                    <TableCell className="font-medium">{contact.name}</TableCell>
                                                    <TableCell>{contact.email}</TableCell>
                                                    <TableCell className="capitalize">
                                                        {contact.category.replace('_', ' ')}
                                                    </TableCell>
                                                    <TableCell>
                                                        <Badge>
                                                            {contact.status}
                                                        </Badge>
                                                    </TableCell>
                                                    <TableCell>
                                                        {new Date(contact.created_at).toLocaleDateString()}
                                                    </TableCell>
                                                    <TableCell className="text-right">
                                                        <Link href={`/admin/contacts/${contact.id}`}>
                                                            <Button variant="primary" size="sm">
                                                                <Eye size={16} />
                                                                View
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
                                    {contactList.map((contact) => (
                                        <Card key={contact.id} className="border border-gray-200">
                                            <CardContent className="p-4">
                                                <div className="space-y-3">
                                                    <div>
                                                        <div className="flex items-start justify-between gap-2 mb-2">
                                                            <p className="font-semibold text-gray-900">{contact.name}</p>
                                                            <Badge>
                                                                {contact.status}
                                                            </Badge>
                                                        </div>
                                                        <div className="space-y-1 text-sm">
                                                            <div className="flex justify-between">
                                                                <span className="text-gray-600">Email:</span>
                                                                <span className="font-medium text-gray-900 truncate ml-2">
                                                                    {contact.email}
                                                                </span>
                                                            </div>
                                                            <div className="flex justify-between">
                                                                <span className="text-gray-600">Category:</span>
                                                                <span className="font-medium text-gray-900 capitalize">
                                                                    {contact.category.replace('_', ' ')}
                                                                </span>
                                                            </div>
                                                            <div className="flex justify-between">
                                                                <span className="text-gray-600">Date:</span>
                                                                <span className="font-medium text-gray-900">
                                                                    {new Date(contact.created_at).toLocaleDateString()}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <Link href={`/admin/contacts/${contact.id}`}>
                                                        <Button variant="primary" size="sm" className="w-full">
                                                            <Eye size={16} />
                                                            View Details
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
