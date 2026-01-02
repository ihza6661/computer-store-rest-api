import { Head } from '@inertiajs/react';

export default function Welcome() {
    return (
        <>
            <Head title="Welcome" />
            <div className="flex min-h-screen flex-col items-center justify-center bg-white p-6">
                <div className="w-full max-w-md text-center">
                    <div className="mb-8">
                        <h1 className="mb-2 text-4xl font-semibold text-gray-900">Computer Store</h1>
                        <p className="text-gray-600">Welcome to our store management system</p>
                    </div>

                    <div className="mb-8">
                        <a
                            href="/login"
                            className="inline-block rounded-full border border-gray-200 bg-white px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-100/60"
                        >
                            Admin Login
                        </a>
                    </div>

                    <div className="mt-12">
                        <p className="text-xs text-gray-500">Built by Cangkir Co.</p>
                    </div>
                </div>
            </div>
        </>
    );
}
