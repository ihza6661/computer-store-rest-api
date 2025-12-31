import { ReactNode } from 'react'
import { Label } from './Label'
import { cn } from '@/lib/utils'

export interface FormFieldProps {
    label?: string
    required?: boolean
    error?: string
    helper?: string
    children: ReactNode
    className?: string
}

export function FormField({ label, required, error, helper, children, className }: FormFieldProps) {
    return (
        <div className={cn('space-y-1.5', className)}>
            {label && <Label required={required}>{label}</Label>}
            {children}
            {helper && !error && <p className="text-xs text-gray-500">{helper}</p>}
            {error && <p className="text-xs text-red-600">{error}</p>}
        </div>
    )
}
