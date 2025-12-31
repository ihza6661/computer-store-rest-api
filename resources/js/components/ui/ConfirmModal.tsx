import { AlertTriangle } from 'lucide-react'
import { Button } from './Button'
import { Modal, ModalFooter } from './Modal'

interface ConfirmModalProps {
    open: boolean
    onClose: () => void
    onConfirm: () => void
    title: string
    description?: string
    confirmText?: string
    cancelText?: string
    variant?: 'primary'
    loading?: boolean
}

export function ConfirmModal({
    open,
    onClose,
    onConfirm,
    title,
    description,
    confirmText = 'Confirm',
    cancelText = 'Cancel',
    variant = 'primary',
    loading = false,
}: ConfirmModalProps) {
    const handleConfirm = () => {
        onConfirm()
        if (!loading) {
            onClose()
        }
    }

    return (
        <Modal open={open} onClose={onClose}>
            <div className="flex gap-4">
                <div className="flex-shrink-0">
                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                        <AlertTriangle className="h-5 w-5 text-gray-600" />
                    </div>
                </div>
                <div className="flex-1">
                    <h3 className="text-lg font-semibold text-gray-800 mb-2">{title}</h3>
                    {description && <p className="text-sm text-gray-600">{description}</p>}
                </div>
            </div>

            <ModalFooter>
                <Button variant="secondary" onClick={onClose} disabled={loading}>
                    {cancelText}
                </Button>
                <Button variant="primary" onClick={handleConfirm} loading={loading}>
                    {confirmText}
                </Button>
            </ModalFooter>
        </Modal>
    )
}
