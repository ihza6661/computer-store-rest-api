import { ReactNode, useEffect, useRef } from 'react'
import { X } from 'lucide-react'
import { cn } from '@/lib/utils'

interface ModalProps {
    open: boolean
    onClose: () => void
    children: ReactNode
    className?: string
    title?: string
}

export function Modal({ open, onClose, children, className, title }: ModalProps) {
    const modalRef = useRef<HTMLDivElement>(null)

    useEffect(() => {
        if (!open) return

        const handleEscape = (e: KeyboardEvent) => {
            if (e.key === 'Escape') onClose()
        }

        const handleClickOutside = (e: MouseEvent) => {
            if (modalRef.current && e.target === modalRef.current) {
                onClose()
            }
        }

        // Prevent body scroll when modal is open
        document.body.style.overflow = 'hidden'

        document.addEventListener('keydown', handleEscape)
        document.addEventListener('mousedown', handleClickOutside)

        return () => {
            document.body.style.overflow = 'unset'
            document.removeEventListener('keydown', handleEscape)
            document.removeEventListener('mousedown', handleClickOutside)
        }
    }, [open, onClose])

    if (!open) return null

    return (
        <div
            ref={modalRef}
            className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30 backdrop-blur-[2px]"
            role="dialog"
            aria-modal="true"
        >
            <div
                className={cn(
                    'relative w-full max-w-lg max-h-[90vh] overflow-y-auto',
                    'bg-white rounded-lg shadow',
                    'md:max-h-[85vh]',
                    className
                )}
            >
                {title && (
                    <div className="flex items-center justify-between p-4 md:p-6 border-b border-gray-200">
                        <h2 className="text-lg md:text-xl font-semibold text-gray-800">{title}</h2>
                        <button
                            onClick={onClose}
                            className="rounded-lg p-1 hover:bg-gray-100 transition-colors"
                            aria-label="Close modal"
                        >
                            <X className="h-5 w-5 text-gray-600" />
                        </button>
                    </div>
                )}
                {!title && (
                    <button
                        onClick={onClose}
                        className="absolute top-4 right-4 rounded-lg p-1 hover:bg-gray-100 transition-colors z-10"
                        aria-label="Close modal"
                    >
                        <X className="h-5 w-5 text-gray-600" />
                    </button>
                )}
                <div className={cn(title ? 'p-4 md:p-6' : 'p-4 md:p-6')}>{children}</div>
            </div>
        </div>
    )
}

export function ModalHeader({ children, className }: { children: ReactNode; className?: string }) {
    return <div className={cn('mb-4', className)}>{children}</div>
}

export function ModalFooter({ children, className }: { children: ReactNode; className?: string }) {
    return <div className={cn('mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3', className)}>{children}</div>
}
