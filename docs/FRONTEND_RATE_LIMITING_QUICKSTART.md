# Rate Limit Error Handling - Quick Reference

## Installation (Choose One)

```bash
# React Hot Toast (recommended)
npm install react-hot-toast

# Sonner (modern)
npm install sonner

# React Toastify
npm install react-toastify
```

## Setup (One-Time Configuration)

Add to your `app.tsx`:

```tsx
// Using React Hot Toast
import { Toaster, toast } from 'react-hot-toast';
import { setRateLimitToastHandler } from './lib/rate-limit-handler';

setRateLimitToastHandler((error) => {
    toast.error(error.message, {
        duration: Math.min(error.retryAfter * 1000, 10000),
        icon: '⏱️',
    });
});

export default function App() {
    return (
        <>
            <Toaster position="top-right" />
            {/* Your app */}
        </>
    );
}
```

## Basic Usage

```tsx
import { apiPost } from '@/lib/api';
import { RateLimitException } from '@/lib/rate-limit-handler';

async function submitForm(data) {
    try {
        const response = await apiPost('/api/contacts', data);
        // Success handling
    } catch (error) {
        if (error instanceof RateLimitException) {
            // Already shows toast automatically
            // Optional: disable button for error.retryAfter seconds
        }
    }
}
```

## Files Created

1. `resources/js/lib/rate-limit-handler.ts` - Error handler utility
2. `resources/js/lib/api.ts` - Enhanced with auto-handling
3. `docs/FRONTEND_RATE_LIMITING.md` - Full documentation

## Rate Limits

| Endpoint                   | Limit          | Key        |
| -------------------------- | -------------- | ---------- |
| `/api/products`            | 60/min         | IP         |
| `/api/contacts`            | **3/min**      | IP         |
| `/login`                   | 5/min + 10/min | Email + IP |
| `/api/admin/*`             | 120/min        | User ID    |
| `/admin/products/import/*` | 600/min        | User ID    |

## That's It!

Your app now automatically handles rate limit errors with user-friendly notifications.

For advanced usage and examples, see `docs/FRONTEND_RATE_LIMITING.md`.
