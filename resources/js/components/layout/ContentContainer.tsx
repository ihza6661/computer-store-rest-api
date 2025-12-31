import { ReactNode } from 'react'
import { cn } from '@/lib/utils'

interface ContentContainerProps {
    children: ReactNode
    className?: string
    maxWidth?: 'default' | 'wide'
}

export function ContentContainer({ children, className, maxWidth = 'default' }: ContentContainerProps) {
    return (
        <div
            className={cn(
                'mx-auto w-full px-4 md:px-6 lg:px-8',
                maxWidth === 'default' ? 'max-w-7xl' : 'max-w-[1600px]',
                className
            )}
        >
            {children}
        </div>
    )
}
