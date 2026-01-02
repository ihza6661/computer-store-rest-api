import { Button } from '@/components/ui/Button';
import { Head, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';

export default function Login() {
    const { data, setData, post, errors } = useForm({
        email: '',
        password: '',
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post('/login');
    };

    return (
        <>
            <Head title="Login" />
            <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                <div className="w-full max-w-md rounded-lg bg-white p-8 shadow">
                    <div className="mb-8">
                        <h1 className="text-2xl font-bold">Computer Store</h1>
                        <p className="text-gray-600">Admin Dashboard Login</p>
                    </div>

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <label className="mb-1 block text-sm font-medium">Email</label>
                            <input
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.currentTarget.value)}
                                placeholder="admin@store.test"
                                className={`w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none ${
                                    errors.email ? 'border-red-500' : 'border-gray-300'
                                }`}
                            />
                            {errors.email && <p className="mt-1 text-sm text-red-500">{errors.email}</p>}
                        </div>

                        <div>
                            <label className="mb-1 block text-sm font-medium">Password</label>
                            <input
                                type="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.currentTarget.value)}
                                placeholder="••••••••"
                                className={`w-full rounded-lg border px-3 py-2 focus:ring-2 focus:ring-[#0071e3] focus:outline-none ${
                                    errors.password ? 'border-red-500' : 'border-gray-300'
                                }`}
                            />
                            {errors.password && <p className="mt-1 text-sm text-red-500">{errors.password}</p>}
                        </div>

                        <Button type="submit" variant="primary" className="w-full">
                            Sign In
                        </Button>
                    </form>

                    <div className="mt-6 rounded-lg bg-gray-50 p-4 text-sm">
                        <p className="mb-2 font-semibold">Demo Credentials:</p>
                        <p>Email: admin@store.test</p>
                        <p>Password: password</p>
                    </div>
                </div>
            </div>
        </>
    );
}
