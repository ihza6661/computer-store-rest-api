import { cva, type VariantProps } from 'class-variance-authority'
import { ButtonHTMLAttributes, forwardRef } from 'react'
import { cn } from '@/lib/utils'
import { Loader2 } from 'lucide-react'

const buttonVariants = cva(
    'inline-flex items-center justify-center gap-2 rounded-full font-normal text-sm transition-all duration-150 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-1 disabled:pointer-events-none disabled:opacity-40',
    {
        variants: {
            variant: {
                primary: 'bg-accent text-white hover:bg-accent-hover shadow-sm',
                secondary: 'text-text-secondary hover:bg-surface-hover border border-border/0 hover:border-border-muted',
                ghost: 'text-text-tertiary hover:bg-surface-hover',
            },
            size: {
                sm: 'h-8 px-3 text-xs min-h-[32px]',
                md: 'h-9 px-4 text-sm min-h-[36px]',
                lg: 'h-10 px-5 text-sm min-h-[40px]',
            },
        },
        defaultVariants: {
            variant: 'primary',
            size: 'md',
        },
    }
)

export interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement>, VariantProps<typeof buttonVariants> {
    loading?: boolean
    asChild?: boolean
}

const Button = forwardRef<HTMLButtonElement, ButtonProps>(
    ({ className, variant, size, loading, disabled, children, asChild, ...props }, ref) => {
        if (asChild) {
            // When asChild is true, render children directly with classes
            // This is for wrapping Link components
            return <>{children}</>
        }

        return (
            <button
                className={cn(buttonVariants({ variant, size, className }))}
                ref={ref}
                disabled={disabled || loading}
                {...props}
            >
                {loading && <Loader2 className="h-4 w-4 animate-spin" />}
                {children}
            </button>
        )
    }
)

Button.displayName = 'Button'

export { Button, buttonVariants }
