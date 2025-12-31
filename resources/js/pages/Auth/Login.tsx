import { FormEvent } from 'react'
import { Head, useForm } from '@inertiajs/react'
import { Button } from '@/components/ui/Button'

export default function Login() {
  const { data, setData, post, errors } = useForm({
    email: '',
    password: '',
  })

  const handleSubmit = (e: FormEvent) => {
    e.preventDefault()
    post('/login')
  }

  return (
    <>
      <Head title="Login" />
      <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
        <div className="w-full max-w-md bg-white rounded-lg shadow p-8">
          <div className="mb-8">
            <h1 className="text-2xl font-bold">Database Computer</h1>
            <p className="text-gray-600">Admin Dashboard Login</p>
          </div>

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-sm font-medium mb-1">Email</label>
              <input
                type="email"
                value={data.email}
                onChange={(e) => setData('email', e.currentTarget.value)}
                placeholder="admin@store.test"
                className={`w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0071e3] ${
                  errors.email ? 'border-red-500' : 'border-gray-300'
                }`}
              />
              {errors.email && <p className="text-red-500 text-sm mt-1">{errors.email}</p>}
            </div>

            <div>
              <label className="block text-sm font-medium mb-1">Password</label>
              <input
                type="password"
                value={data.password}
                onChange={(e) => setData('password', e.currentTarget.value)}
                placeholder="••••••••"
                className={`w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-[#0071e3] ${
                  errors.password ? 'border-red-500' : 'border-gray-300'
                }`}
              />
              {errors.password && <p className="text-red-500 text-sm mt-1">{errors.password}</p>}
            </div>

            <Button
              type="submit"
              variant="primary"
              className="w-full"
            >
              Sign In
            </Button>
          </form>

          <div className="mt-6 p-4 bg-gray-50 rounded-lg text-sm">
            <p className="font-semibold mb-2">Demo Credentials:</p>
            <p>Email: admin@store.test</p>
            <p>Password: password</p>
          </div>
        </div>
      </div>
    </>
  )
}
