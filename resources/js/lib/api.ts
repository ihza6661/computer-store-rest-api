/**
 * API utility wrapper for authenticated fetch requests
 * Automatically includes credentials (cookies) for session-based authentication
 * Handles 429 rate limit errors gracefully
 */

import { handleRateLimitError, RateLimitException } from './rate-limit-handler';

interface FetchOptions extends RequestInit {
    headers?: HeadersInit;
    // Set to true to disable automatic rate limit error handling for this request
    skipRateLimitHandler?: boolean;
}

/**
 * Wrapper around fetch that automatically includes credentials for authenticated requests
 * Use this for all API calls to /api/admin/* endpoints
 *
 * Automatically handles 429 rate limit errors by:
 * - Showing a user-friendly toast notification
 * - Throwing a RateLimitException with retry information
 */
export async function apiFetch(url: string, options: FetchOptions = {}): Promise<Response> {
    const defaultHeaders: HeadersInit = {
        Accept: 'application/json',
    };

    // Merge default headers with provided headers
    const headers = {
        ...defaultHeaders,
        ...options.headers,
    };

    // Extract custom options
    const { skipRateLimitHandler, ...fetchOptions } = options;

    // Make the fetch request with credentials included
    const response = await fetch(url, {
        ...fetchOptions,
        headers,
        credentials: 'include', // Include cookies for session-based auth
    });

    // Handle 429 rate limit errors
    if (response.status === 429 && !skipRateLimitHandler) {
        const rateLimitError = await handleRateLimitError(response);
        throw new RateLimitException(rateLimitError);
    }

    return response;
}

/**
 * Convenience method for GET requests
 */
export async function apiGet(url: string, options: FetchOptions = {}): Promise<Response> {
    return apiFetch(url, {
        ...options,
        method: 'GET',
    });
}

/**
 * Convenience method for POST requests
 */
export async function apiPost(url: string, body?: unknown, options: FetchOptions = {}): Promise<Response> {
    return apiFetch(url, {
        ...options,
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            ...options.headers,
        },
        body: body ? JSON.stringify(body) : undefined,
    });
}

/**
 * Convenience method for PUT requests
 */
export async function apiPut(url: string, body?: unknown, options: FetchOptions = {}): Promise<Response> {
    return apiFetch(url, {
        ...options,
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            ...options.headers,
        },
        body: body ? JSON.stringify(body) : undefined,
    });
}

/**
 * Convenience method for DELETE requests
 */
export async function apiDelete(url: string, options: FetchOptions = {}): Promise<Response> {
    return apiFetch(url, {
        ...options,
        method: 'DELETE',
    });
}
