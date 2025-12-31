import { BackLink } from '@/components/ui/BackLink';
import { Badge } from '@/components/ui/Badge';
import { Button } from '@/components/ui/Button';
import { Head, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AdminLayout from '../Layouts/AdminLayout';

interface Contact {
    id: number;
    name: string;
    email: string;
    phone: string;
    category: string;
    message: string;
    status: string;
    admin_reply: string | null;
    created_at: string;
}

interface Props {
    contact: Contact;
}

export default function ShowContact({ contact: initialContact }: Props) {
    const [contact, setContact] = useState<Contact>(initialContact);
    const [successMessage, setSuccessMessage] = useState('');

    const { data, setData, post, processing, errors, reset } = useForm({
        status: 'replied' as const,
        admin_reply: initialContact.admin_reply || '',
        _method: 'PUT' as const,
    });

    const handleReply = (e: React.FormEvent) => {
        e.preventDefault();
        setSuccessMessage('');

        post(`/admin/contacts/${contact.id}`, {
            onSuccess: (response: any) => {
                setSuccessMessage('Reply sent successfully!');
                // Update local contact state with the response
                if (response.props?.contact) {
                    setContact(response.props.contact);
                }
            },
        });
    };

    return (
        <AdminLayout>
            <Head title="View Contact" />
            <div className="space-y-6">
                <div>
                    <BackLink href="/admin/contacts">Contacts</BackLink>
                    <h1 className="text-3xl font-bold">Contact Submission</h1>
                </div>

                <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div className="rounded-lg bg-white p-6 shadow">
                        <h2 className="mb-4 text-lg font-bold">Submission Details</h2>
                        <div className="space-y-4">
                            <div>
                                <label className="text-sm text-gray-600">Name</label>
                                <p className="font-medium">{contact.name}</p>
                            </div>
                            <div>
                                <label className="text-sm text-gray-600">Email</label>
                                <p className="font-medium">{contact.email}</p>
                            </div>
                            <div>
                                <label className="text-sm text-gray-600">Phone</label>
                                <p className="font-medium">{contact.phone || 'N/A'}</p>
                            </div>
                            <div>
                                <label className="text-sm text-gray-600">Category</label>
                                <p className="font-medium capitalize">{contact.category.replace('_', ' ')}</p>
                            </div>
                            <div>
                                <label className="text-sm text-gray-600">Status</label>
                                <div className="mt-1">
                                    <Badge>{contact.status}</Badge>
                                </div>
                            </div>
                            <div>
                                <label className="text-sm text-gray-600">Submitted</label>
                                <p className="font-medium">{new Date(contact.created_at).toLocaleString()}</p>
                            </div>
                        </div>
                    </div>

                    <div className="rounded-lg bg-white p-6 shadow">
                        <h2 className="mb-4 text-lg font-bold">Message</h2>
                        <p className="whitespace-pre-wrap">{contact.message}</p>
                    </div>
                </div>

                <div className="rounded-lg bg-white p-6 shadow">
                    <h2 className="mb-4 text-lg font-bold">Admin Reply</h2>

                    {successMessage && <div className="mb-4 rounded-lg border border-gray-300 bg-gray-100 p-3 text-gray-700">{successMessage}</div>}

                    <form onSubmit={handleReply} className="space-y-4">
                        <textarea
                            value={data.admin_reply}
                            onChange={(e) => setData('admin_reply', e.currentTarget.value)}
                            placeholder="Send a reply to the customer..."
                            className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                            rows={6}
                        />
                        {errors.admin_reply && <p className="text-sm text-red-500">{errors.admin_reply}</p>}
                        <Button type="submit" disabled={processing} variant="primary">
                            {processing ? 'Sending...' : 'Send Reply'}
                        </Button>
                    </form>
                </div>
            </div>
        </AdminLayout>
    );
}
