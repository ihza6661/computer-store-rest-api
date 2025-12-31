import { useEffect, useState } from 'react'
import { X, CheckCircle2, XCircle } from 'lucide-react'
import { cn } from '@/lib/utils'

interface ToastProps {
    message: string
    type: 'success' | 'error'
    onClose: () => void
    duration?: number
}

export function Toast({ message, type, onClose, duration = 5000 }: ToastProps) {
    const [progress, setProgress] = useState(100)

    useEffect(() => {
        const startTime = Date.now()
        const interval = setInterval(() => {
            const elapsed = Date.now() - startTime
            const remaining = Math.max(0, 100 - (elapsed / duration) * 100)
            setProgress(remaining)

            if (remaining === 0) {
                clearInterval(interval)
                onClose()
            }
        }, 16)

        return () => clearInterval(interval)
    }, [duration, onClose])

    return (
        <div
            className={cn(
                'pointer-events-auto relative w-full max-w-sm overflow-hidden rounded-lg bg-white shadow ring-1 ring-black ring-opacity-5',
                'transform transition-all duration-300 ease-in-out'
            )}
        >
            <div className="p-4">
                <div className="flex items-start gap-3">
                    <div className="flex-shrink-0">
                        {type === 'success' ? (
                            <CheckCircle2 className="h-5 w-5 text-gray-600" />
                        ) : (
                            <XCircle className="h-5 w-5 text-red-600" />
                        )}
                    </div>
                    <div className="flex-1 pt-0.5">
                        <p className="text-sm font-medium text-gray-900">{message}</p>
                    </div>
                    <div className="flex-shrink-0">
                        <button
                            onClick={onClose}
                            className="inline-flex rounded-md p-1.5 hover:bg-gray-100 transition-colors"
                        >
                            <X className="h-4 w-4 text-gray-600" />
                        </button>
                    </div>
                </div>
            </div>
            <div className="h-1 bg-gray-100">
                <div
                    className="h-full transition-all duration-100 ease-linear bg-gray-400"
                    style={{ width: `${progress}%` }}
                />
            </div>
        </div>
    )
}

interface ToastContainerProps {
    toasts: Array<{ id: string; message: string; type: 'success' | 'error' }>
    onRemove: (id: string) => void
}

export function ToastContainer({ toasts, onRemove }: ToastContainerProps) {
    if (toasts.length === 0) return null

    return (
        <div
            className="fixed top-4 right-4 z-50 flex flex-col gap-2 pointer-events-none"
            aria-live="polite"
            aria-atomic="true"
        >
            {toasts.map((toast) => (
                <Toast key={toast.id} message={toast.message} type={toast.type} onClose={() => onRemove(toast.id)} />
            ))}
        </div>
    )
}
