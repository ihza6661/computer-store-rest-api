# Frontend Rate Limit Error Handling

This document explains how to handle 429 rate limit errors in the React/TypeScript frontend.

## Overview

The frontend automatically handles 429 errors from the API by:

1. Detecting the 429 status code
2. Extracting the `Retry-After` header
3. Displaying a user-friendly toast notification
4. Providing structured error information for custom handling

## Files

- **`resources/js/lib/rate-limit-handler.ts`** - Core rate limit error handling logic
- **`resources/js/lib/api.ts`** - Enhanced with automatic 429 error handling

## Quick Start

### 1. Install a Toast Library (Optional but Recommended)

Choose one of these popular toast libraries:

```bash
# React Hot Toast (recommended)
npm install react-hot-toast

# OR Sonner (modern alternative)
npm install sonner

# OR React Toastify
npm install react-toastify
```

### 2. Configure Toast Handler

In your `app.tsx` (or main app entry point), configure the global toast handler:

#### Option A: Using React Hot Toast

```tsx
import { Toaster } from 'react-hot-toast';
import { setRateLimitToastHandler } from './lib/rate-limit-handler';
import toast from 'react-hot-toast';

// Configure the rate limit toast handler
setRateLimitToastHandler((error) => {
    toast.error(error.message, {
        duration: Math.min(error.retryAfter * 1000, 10000), // Max 10 seconds
        icon: '⏱️',
        style: {
            background: '#ef4444',
            color: '#fff',
        },
    });
});

function App() {
    return (
        <>
            <Toaster position="top-right" />
            {/* Your app components */}
        </>
    );
}

export default App;
```

#### Option B: Using Sonner

```tsx
import { Toaster, toast } from 'sonner';
import { setRateLimitToastHandler } from './lib/rate-limit-handler';

setRateLimitToastHandler((error) => {
    toast.error(error.message, {
        duration: Math.min(error.retryAfter * 1000, 10000),
    });
});

function App() {
    return (
        <>
            <Toaster position="top-right" richColors />
            {/* Your app components */}
        </>
    );
}

export default App;
```

#### Option C: Using React Toastify

```tsx
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { setRateLimitToastHandler } from './lib/rate-limit-handler';

setRateLimitToastHandler((error) => {
    toast.error(error.message, {
        autoClose: Math.min(error.retryAfter * 1000, 10000),
    });
});

function App() {
    return (
        <>
            <ToastContainer position="top-right" />
            {/* Your app components */}
        </>
    );
}

export default App;
```

#### Option D: No Toast Library (Console Only)

If you don't want to install a toast library, the default console-based handler will be used automatically. You can also create a custom implementation:

```tsx
import { setRateLimitToastHandler } from './lib/rate-limit-handler';

setRateLimitToastHandler((error) => {
    // Custom alert or UI notification
    alert(`Rate limit exceeded: ${error.message}`);

    // Or use a custom notification component
    showCustomNotification({
        type: 'error',
        message: error.message,
        duration: error.retryAfter * 1000,
    });
});
```

### 3. Use API Functions Normally

The API wrapper now automatically handles 429 errors:

```tsx
import { apiGet, apiPost } from '@/lib/api';
import { RateLimitException } from '@/lib/rate-limit-handler';

async function fetchProducts() {
    try {
        const response = await apiGet('/api/products');
        const data = await response.json();
        return data;
    } catch (error) {
        if (error instanceof RateLimitException) {
            // Rate limit error already handled with toast
            // Optionally, you can add custom logic here
            console.log(`Rate limited. Retry after ${error.retryAfter} seconds`);
            console.log(`Can retry at: ${error.retryAt.toLocaleTimeString()}`);

            // You could disable a button, show a countdown, etc.
            return null;
        }

        // Handle other errors
        throw error;
    }
}
```

## Usage Examples

### Example 1: Contact Form Submission

```tsx
import { apiPost } from '@/lib/api';
import { RateLimitException } from '@/lib/rate-limit-handler';
import { useState } from 'react';

export function ContactForm() {
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [isRateLimited, setIsRateLimited] = useState(false);

    async function handleSubmit(formData: FormData) {
        setIsSubmitting(true);
        setIsRateLimited(false);

        try {
            const response = await apiPost('/api/contacts', {
                name: formData.get('name'),
                email: formData.get('email'),
                category: formData.get('category'),
                message: formData.get('message'),
            });

            if (response.ok) {
                toast.success('Message sent successfully!');
            }
        } catch (error) {
            if (error instanceof RateLimitException) {
                // Rate limit toast already shown
                setIsRateLimited(true);

                // Re-enable after retry period
                setTimeout(() => {
                    setIsRateLimited(false);
                }, error.retryAfter * 1000);
            } else {
                toast.error('Failed to send message');
            }
        } finally {
            setIsSubmitting(false);
        }
    }

    return (
        <form onSubmit={handleSubmit}>
            {/* form fields */}
            <button type="submit" disabled={isSubmitting || isRateLimited}>
                {isRateLimited ? 'Please wait...' : 'Send Message'}
            </button>
        </form>
    );
}
```

### Example 2: Login Form

```tsx
import { router } from '@inertiajs/react';
import { RateLimitException } from '@/lib/rate-limit-handler';
import { useState } from 'react';

export function LoginForm() {
    const [isRateLimited, setIsRateLimited] = useState(false);

    function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        const formData = new FormData(e.currentTarget);

        router.post('/login', Object.fromEntries(formData), {
            onError: (errors) => {
                // Check if the error is a rate limit error
                if (errors.email?.includes('Too many login attempts')) {
                    setIsRateLimited(true);

                    // Re-enable after 1 minute (login limit is 5/min)
                    setTimeout(() => setIsRateLimited(false), 60000);
                }
            },
        });
    }

    return (
        <form onSubmit={handleSubmit}>
            {/* form fields */}
            <button type="submit" disabled={isRateLimited}>
                {isRateLimited ? 'Too many attempts. Please wait...' : 'Login'}
            </button>

            {isRateLimited && (
                <p className="mt-2 text-sm text-red-600">You've exceeded the login attempt limit. Please wait a minute before trying again.</p>
            )}
        </form>
    );
}
```

### Example 3: Product List with Retry

```tsx
import { apiGet } from '@/lib/api';
import { RateLimitException } from '@/lib/rate-limit-handler';
import { useEffect, useState } from 'react';

export function ProductList() {
    const [products, setProducts] = useState([]);
    const [rateLimitInfo, setRateLimitInfo] = useState<{
        isLimited: boolean;
        retryAt: Date | null;
    }>({ isLimited: false, retryAt: null });

    async function loadProducts() {
        try {
            const response = await apiGet('/api/products');
            const data = await response.json();
            setProducts(data);
            setRateLimitInfo({ isLimited: false, retryAt: null });
        } catch (error) {
            if (error instanceof RateLimitException) {
                setRateLimitInfo({
                    isLimited: true,
                    retryAt: error.retryAt,
                });

                // Automatically retry after the rate limit expires
                setTimeout(() => {
                    loadProducts();
                }, error.retryAfter * 1000);
            }
        }
    }

    useEffect(() => {
        loadProducts();
    }, []);

    if (rateLimitInfo.isLimited) {
        return (
            <div className="py-8 text-center">
                <p className="text-gray-600">Rate limit exceeded. Retrying at {rateLimitInfo.retryAt?.toLocaleTimeString()}...</p>
            </div>
        );
    }

    return (
        <div>
            {products.map((product) => (
                <ProductCard key={product.id} product={product} />
            ))}
        </div>
    );
}
```

### Example 4: Bulk Operations with Countdown

```tsx
import { apiPost } from '@/lib/api';
import { RateLimitException } from '@/lib/rate-limit-handler';
import { useState, useEffect } from 'react';

export function BulkImportButton() {
    const [countdown, setCountdown] = useState(0);

    useEffect(() => {
        if (countdown > 0) {
            const timer = setTimeout(() => setCountdown(countdown - 1), 1000);
            return () => clearTimeout(timer);
        }
    }, [countdown]);

    async function handleImport(file: File) {
        try {
            const formData = new FormData();
            formData.append('file', file);

            const response = await apiPost('/admin/products/import/store', formData);

            if (response.ok) {
                toast.success('Import started successfully!');
            }
        } catch (error) {
            if (error instanceof RateLimitException) {
                // Start countdown
                setCountdown(error.retryAfter);
            } else {
                toast.error('Import failed');
            }
        }
    }

    return (
        <button onClick={() => handleImport(selectedFile)} disabled={countdown > 0}>
            {countdown > 0 ? `Please wait ${countdown}s...` : 'Import Products'}
        </button>
    );
}
```

## Advanced Usage

### Custom Rate Limit Handling

If you want to handle rate limits differently for specific requests, use the `skipRateLimitHandler` option:

```tsx
import { apiFetch } from '@/lib/api';
import { parseRateLimitError } from '@/lib/rate-limit-handler';

async function customRateLimitHandling() {
    const response = await apiFetch('/api/products', {
        skipRateLimitHandler: true, // Disable automatic handling
    });

    if (response.status === 429) {
        // Custom handling
        const error = await parseRateLimitError(response);

        // Show custom UI
        showCustomRateLimitModal({
            message: error.message,
            retryAfter: error.retryAfter,
        });

        return null;
    }

    return response.json();
}
```

### Type-Safe Error Handling

```tsx
import { isRateLimitError, RateLimitException } from '@/lib/rate-limit-handler';

async function fetchData() {
    try {
        const response = await apiGet('/api/products');
        return response.json();
    } catch (error) {
        // Type guard for rate limit errors
        if (isRateLimitError(error)) {
            console.log('Rate limited:', error.retryAfter, 'seconds');
            return null;
        }

        // Or check for the exception class
        if (error instanceof RateLimitException) {
            console.log('Retry at:', error.retryAt);
            return null;
        }

        // Other errors
        throw error;
    }
}
```

### Global Error Boundary

Create a global error boundary to catch rate limit errors:

```tsx
import { Component, ReactNode } from 'react';
import { RateLimitException } from '@/lib/rate-limit-handler';

interface Props {
    children: ReactNode;
}

interface State {
    hasError: boolean;
    error: Error | null;
}

export class RateLimitErrorBoundary extends Component<Props, State> {
    constructor(props: Props) {
        super(props);
        this.state = { hasError: false, error: null };
    }

    static getDerivedStateFromError(error: Error) {
        return { hasError: true, error };
    }

    componentDidCatch(error: Error) {
        if (error instanceof RateLimitException) {
            console.log('Rate limit caught by boundary:', error.message);
            // Error already handled by toast, just log it
        }
    }

    render() {
        if (this.state.hasError && this.state.error instanceof RateLimitException) {
            return (
                <div className="py-8 text-center">
                    <p className="text-red-600">Rate limit exceeded</p>
                    <p className="mt-2 text-sm text-gray-600">Please try again in {this.state.error.retryAfter} seconds</p>
                    <button onClick={() => this.setState({ hasError: false, error: null })} className="mt-4 rounded bg-blue-600 px-4 py-2 text-white">
                        Retry
                    </button>
                </div>
            );
        }

        return this.props.children;
    }
}
```

## Rate Limit Information

Your API has the following rate limits:

| Endpoint                                        | Limit                            | Notes                          |
| ----------------------------------------------- | -------------------------------- | ------------------------------ |
| Public API (`/api/products`, `/api/categories`) | 60/min                           | Per IP address                 |
| Contact Form (`/api/contacts`)                  | 3/min                            | Per IP address, strict         |
| Login (`/login`)                                | 5/min per email<br>10/min per IP | Dual protection                |
| Admin API (`/api/admin/*`)                      | 120/min                          | Per authenticated user         |
| Product Imports (`/admin/products/import/*`)    | 600/min                          | High limit for bulk operations |

## Response Headers

When a rate limit is active, the API returns these headers:

- `X-RateLimit-Limit` - Total requests allowed in the time window
- `X-RateLimit-Remaining` - Requests remaining in current window
- `Retry-After` - Seconds until the rate limit resets (only on 429 responses)

## Troubleshooting

### Toast notifications not appearing

Make sure you've called `setRateLimitToastHandler()` in your app initialization:

```tsx
// app.tsx
import { setRateLimitToastHandler } from './lib/rate-limit-handler';
import toast from 'react-hot-toast';

setRateLimitToastHandler((error) => {
    toast.error(error.message, { duration: error.retryAfter * 1000 });
});
```

### Rate limit errors not caught

Ensure you're using the API functions from `lib/api.ts`:

```tsx
// ✅ Correct
import { apiGet } from '@/lib/api';
const response = await apiGet('/api/products');

// ❌ Wrong (bypasses rate limit handling)
const response = await fetch('/api/products');
```

### TypeScript errors with RateLimitException

Make sure you're importing from the correct path:

```tsx
import { RateLimitException, isRateLimitError } from '@/lib/rate-limit-handler';
```

## Best Practices

1. **Always catch rate limit exceptions** in user-facing operations (forms, buttons, etc.)
2. **Disable UI elements** during the retry period to prevent user frustration
3. **Show countdown timers** for better UX when rate limited
4. **Use automatic retries** sparingly - only for background operations
5. **Log rate limit events** to help identify usage patterns
6. **Test rate limits** in development to ensure proper handling

## Testing

To test rate limit handling in development:

```tsx
// Temporarily reduce limits for testing
async function testRateLimit() {
    // Make multiple rapid requests
    for (let i = 0; i < 5; i++) {
        try {
            await apiPost('/api/contacts', {
                name: 'Test',
                email: 'test@example.com',
                category: 'general',
                message: 'Test message',
            });
        } catch (error) {
            if (error instanceof RateLimitException) {
                console.log(`Rate limited on request ${i + 1}`);
                console.log(`Retry after: ${error.retryAfter}s`);
            }
        }
    }
}
```

---

**Last Updated:** January 4, 2026
**Requires:** Laravel API with rate limiting enabled
**Compatible With:** React 19, TypeScript 5.7+
