import { ReactNode } from 'react'
import { cn } from '@/lib/utils'

interface PageHeaderProps {
    title: string
    description?: string
    actions?: ReactNode
    className?: string
}

export function PageHeader({ title, description, actions, className }: PageHeaderProps) {
    return (
        <div className={cn('flex flex-col gap-4 md:flex-row md:items-center md:justify-between', className)}>
            <div className="space-y-1">
                <h1 className="text-2xl md:text-3xl font-bold text-gray-900">{title}</h1>
                {description && <p className="text-sm md:text-base text-gray-600">{description}</p>}
            </div>
            {actions && <div className="flex flex-wrap items-center gap-2">{actions}</div>}
        </div>
    )
}
