import { SelectHTMLAttributes, forwardRef } from 'react'
import { ChevronDown } from 'lucide-react'
import { cn } from '@/lib/utils'

export interface SelectProps extends SelectHTMLAttributes<HTMLSelectElement> {
    error?: boolean
}

const Select = forwardRef<HTMLSelectElement, SelectProps>(({ className, error, children, ...props }, ref) => {
    return (
        <div className="relative">
            <select
                className={cn(
                    'flex h-11 w-full rounded-lg border border-gray-300 bg-white px-3 py-2 pr-10 text-sm transition-colors appearance-none',
                    'focus:outline-none focus:ring-2 focus:ring-[#0071e3] focus:border-transparent',
                    'disabled:cursor-not-allowed disabled:opacity-50 disabled:bg-gray-50',
                    'min-h-[44px]',
                    'text-gray-700',
                    error && 'border-red-500 focus:ring-red-500',
                    className
                )}
                ref={ref}
                {...props}
            >
                {children}
            </select>
            <ChevronDown
                className="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none"
                aria-hidden="true"
            />
        </div>
    )
})

Select.displayName = 'Select'

export { Select }
