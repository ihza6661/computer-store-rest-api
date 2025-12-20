/**
 * API utility wrapper for authenticated fetch requests
 * Automatically includes credentials (cookies) for session-based authentication
 */

interface FetchOptions extends RequestInit {
  headers?: HeadersInit
}

/**
 * Wrapper around fetch that automatically includes credentials for authenticated requests
 * Use this for all API calls to /api/admin/* endpoints
 */
export async function apiFetch(url: string, options: FetchOptions = {}): Promise<Response> {
  const defaultHeaders: HeadersInit = {
    'Accept': 'application/json',
  }

  // Merge default headers with provided headers
  const headers = {
    ...defaultHeaders,
    ...options.headers,
  }

  // Make the fetch request with credentials included
  return fetch(url, {
    ...options,
    headers,
    credentials: 'include', // Include cookies for session-based auth
  })
}

/**
 * Convenience method for GET requests
 */
export async function apiGet(url: string, options: FetchOptions = {}): Promise<Response> {
  return apiFetch(url, {
    ...options,
    method: 'GET',
  })
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
  })
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
  })
}

/**
 * Convenience method for DELETE requests
 */
export async function apiDelete(url: string, options: FetchOptions = {}): Promise<Response> {
  return apiFetch(url, {
    ...options,
    method: 'DELETE',
  })
}
