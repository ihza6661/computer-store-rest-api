import { Link } from '@inertiajs/react'

interface BackLinkProps {
  href: string
  children: React.ReactNode
}

export function BackLink({ href, children }: BackLinkProps) {
  return (
    <Link
      href={href}
      className="text-sm text-gray-500 hover:text-gray-700 inline-flex items-center gap-1 mb-2"
    >
      ← {children}
    </Link>
  )
}
