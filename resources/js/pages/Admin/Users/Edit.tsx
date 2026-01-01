import { BackLink } from '@/components/ui/BackLink';
import { Button } from '@/components/ui/Button';
import { Head, Link, useForm } from '@inertiajs/react';
import AdminLayout from '../Layouts/AdminLayout';

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    created_at: string;
}

interface Props {
    user: User;
}

export default function EditUser({ user }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        name: user.name,
        email: user.email,
        role: user.role,
        password: '',
        _method: 'PUT' as const,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(`/admin/users/${user.id}`, {
            onSuccess: () => {
                // Inertia will handle the redirect
            },
        });
    };

    return (
        <AdminLayout>
            <Head title="Edit User" />
            <div className="space-y-6">
                <div>
                    <BackLink href="/admin/users">Users</BackLink>
                    <h1 className="text-3xl font-bold">Edit User</h1>
                    <p className="text-sm text-gray-500">Update user information</p>
                </div>

                <div className="rounded-lg border border-gray-200/60 bg-white p-6">
                    <h2 className="mb-6 text-lg font-bold">User Details</h2>

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <label className="mb-1 block text-sm font-medium">Name *</label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={(e) => setData({ ...data, name: e.currentTarget.value })}
                                placeholder="Full name"
                                className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                            />
                            {errors.name && <p className="text-sm text-red-500">{errors.name}</p>}
                        </div>

                        <div>
                            <label className="mb-1 block text-sm font-medium">Email *</label>
                            <input
                                type="email"
                                value={data.email}
                                onChange={(e) => setData({ ...data, email: e.currentTarget.value })}
                                placeholder="user@example.com"
                                className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                            />
                            {errors.email && <p className="text-sm text-red-500">{errors.email}</p>}
                        </div>

                        <div>
                            <label className="mb-1 block text-sm font-medium">Role *</label>
                            <select
                                value={data.role}
                                onChange={(e) => setData({ ...data, role: e.currentTarget.value })}
                                className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                            >
                                <option value="admin">Admin</option>
                                <option value="editor">Editor</option>
                                <option value="viewer">Viewer</option>
                            </select>
                            {errors.role && <p className="text-sm text-red-500">{errors.role}</p>}
                        </div>

                        <div className="mt-6">
                            <label className="mb-1 block text-sm font-medium">Password</label>
                            <input
                                type="password"
                                value={data.password}
                                onChange={(e) => setData({ ...data, password: e.currentTarget.value })}
                                placeholder="Leave blank to keep current password"
                                className="w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none"
                            />
                            <p className="mt-1 text-xs text-gray-500">Leave blank if you don't want to change the password</p>
                            {errors.password && <p className="text-sm text-red-500">{errors.password}</p>}
                        </div>

                        <div className="flex justify-end gap-2">
                            <Button type="submit" disabled={processing} variant="primary">
                                {processing ? 'Saving...' : 'Save Changes'}
                            </Button>
                            <Link href="/admin/users">
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
