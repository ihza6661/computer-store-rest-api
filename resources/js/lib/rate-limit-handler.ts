/**
 * Rate Limit Error Handler
 *
 * Handles 429 Too Many Requests errors from the API by:
 * - Extracting retry-after information from response headers
 * - Providing user-friendly error messages
 * - Supporting custom toast notification handlers
 */

export interface RateLimitError {
    status: 429;
    message: string;
    retryAfter: number; // seconds until retry is allowed
    retryAt: Date; // exact time when retry is allowed
}

export interface RateLimitNotificationOptions {
    title?: string;
    duration?: number;
    icon?: string;
}

/**
 * Default toast notification handler
 * Override this in your app initialization to use your preferred toast library
 */
let toastHandler: (error: RateLimitError, options?: RateLimitNotificationOptions) => void = (error, options) => {
    // Default console-based notification (fallback if no toast library is configured)
    console.warn(`[Rate Limit] ${options?.title || 'Too many requests'}. Please wait ${error.retryAfter} seconds before trying again.`);
};

/**
 * Configure the global toast handler for rate limit errors
 * Call this once in your app initialization (e.g., in app.tsx)
 *
 * @example
 * // Using react-hot-toast
 * import toast from 'react-hot-toast'
 * setRateLimitToastHandler((error) => {
 *   toast.error(error.message, { duration: error.retryAfter * 1000 })
 * })
 *
 * @example
 * // Using sonner
 * import { toast } from 'sonner'
 * setRateLimitToastHandler((error) => {
 *   toast.error(error.message, { duration: error.retryAfter * 1000 })
 * })
 */
export function setRateLimitToastHandler(handler: (error: RateLimitError, options?: RateLimitNotificationOptions) => void): void {
    toastHandler = handler;
}

/**
 * Parses a 429 response and extracts rate limit information
 */
export async function parseRateLimitError(response: Response): Promise<RateLimitError> {
    // Extract Retry-After header (in seconds)
    const retryAfterHeader = response.headers.get('Retry-After');
    const retryAfter = retryAfterHeader ? parseInt(retryAfterHeader, 10) : 60;

    // Calculate the exact retry time
    const retryAt = new Date(Date.now() + retryAfter * 1000);

    // Try to parse the response body for a custom message
    let message = 'Too many requests. Please try again later.';
    try {
        const data = await response.json();
        if (data.message) {
            message = data.message;
        }
    } catch {
        // If response body is not JSON, use default message
    }

    // Format a user-friendly message with retry time
    const friendlyMessage = formatRateLimitMessage(message, retryAfter);

    return {
        status: 429,
        message: friendlyMessage,
        retryAfter,
        retryAt,
    };
}

/**
 * Formats a user-friendly rate limit message
 */
function formatRateLimitMessage(originalMessage: string, retryAfter: number): string {
    // If the original message already includes timing info, use it
    if (originalMessage.toLowerCase().includes('try again')) {
        return originalMessage;
    }

    // Format retry time in a human-readable way
    let timeString: string;
    if (retryAfter < 60) {
        timeString = `${retryAfter} second${retryAfter !== 1 ? 's' : ''}`;
    } else {
        const minutes = Math.ceil(retryAfter / 60);
        timeString = `${minutes} minute${minutes !== 1 ? 's' : ''}`;
    }

    return `${originalMessage} Please wait ${timeString} before trying again.`;
}

/**
 * Handles a 429 rate limit error by showing a notification
 * and returning a structured error object
 */
export async function handleRateLimitError(response: Response, options?: RateLimitNotificationOptions): Promise<RateLimitError> {
    const error = await parseRateLimitError(response);

    // Trigger the configured toast handler
    toastHandler(error, {
        title: options?.title || 'Rate Limit Exceeded',
        duration: options?.duration || error.retryAfter * 1000,
        icon: options?.icon,
    });

    return error;
}

/**
 * Check if an error is a rate limit error
 */
export function isRateLimitError(error: unknown): error is RateLimitError {
    return typeof error === 'object' && error !== null && 'status' in error && error.status === 429 && 'retryAfter' in error;
}

/**
 * Custom error class for rate limit errors
 * Useful for throwing and catching rate limit errors in a type-safe way
 */
export class RateLimitException extends Error {
    public readonly status = 429;
    public readonly retryAfter: number;
    public readonly retryAt: Date;

    constructor(error: RateLimitError) {
        super(error.message);
        this.name = 'RateLimitException';
        this.retryAfter = error.retryAfter;
        this.retryAt = error.retryAt;

        // Maintains proper stack trace for where our error was thrown (only available on V8)
        if (Error.captureStackTrace) {
            Error.captureStackTrace(this, RateLimitException);
        }
    }
}
