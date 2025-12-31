import { cva, type VariantProps } from 'class-variance-authority'
import { HTMLAttributes, forwardRef } from 'react'
import { cn } from '@/lib/utils'

const badgeVariants = cva(
    'inline-flex items-center text-xs font-normal',
    {
        variants: {
            variant: {
                default: 'text-gray-600',
            },
        },
        defaultVariants: {
            variant: 'default',
        },
    }
)

export interface BadgeProps extends HTMLAttributes<HTMLSpanElement>, VariantProps<typeof badgeVariants> {}

const Badge = forwardRef<HTMLSpanElement, BadgeProps>(({ className, variant, ...props }, ref) => {
    return <span ref={ref} className={cn(badgeVariants({ variant }), className)} {...props} />
})

Badge.displayName = 'Badge'

export { Badge, badgeVariants }
