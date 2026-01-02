# Computer Store REST API

Laravel + Inertia + React admin panel for Computer Store store.

## Development Setup

### Prerequisites

- PHP 8.1+
- Composer
- Node.js 18+
- MySQL
- Git

### Initial Setup

1. Clone the repository
2. Install dependencies:

    ```bash
    composer install
    npm install
    ```

3. Copy `.env.example` to `.env`:

    ```bash
    cp .env.example .env
    ```

4. Generate application key:

    ```bash
    php artisan key:generate
    ```

5. Configure your database in `.env`:

    ```env
    DB_DATABASE=computer_store
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

6. Run migrations and seeders:
    ```bash
    php artisan migrate --seed
    ```

### Running Development Servers

#### Option 1: Using the Helper Script (Recommended)

Start both Laravel and Vite dev servers with one command:

```bash
./dev.sh
```

This will start:

- Laravel dev server at `http://localhost:8000`
- Vite dev server at `http://localhost:5173` (for HMR)

Press `Ctrl+C` to stop both servers.

#### Option 2: Manual Start

Start servers in separate terminals:

**Terminal 1 - Laravel:**

```bash
php artisan serve
```

**Terminal 2 - Vite:**

```bash
npm run dev
```

### Admin Login Credentials

**Local Development:**

```
Email: admin@store.test
Password: password
```

**Production (Heroku):**

```
Email: admin@store.test
Password: password
```

### Available Commands

- `npm run dev` - Start Vite dev server with HMR
- `npm run build` - Build assets for production
- `npm run lint` - Lint and fix code
- `npm run format` - Format code with Prettier
- `npm run types` - Check TypeScript types
- `php artisan serve` - Start Laravel dev server
- `php artisan migrate` - Run database migrations
- `php artisan db:seed` - Seed database
- `php artisan users:update-domains` - Update user email domains

### Troubleshooting

#### CORS Error: Assets Loading from Heroku URL

If you see CORS errors trying to load assets from `computer-store-api-dd14765dc7ef.herokuapp.com`:

1. Delete the production build artifacts:

    ```bash
    rm -rf public/build
    ```

2. Start the Vite dev server:

    ```bash
    npm run dev
    ```

3. Ensure `public/build/` is in `.gitignore` (it should be by default)

#### Assets Not Hot Reloading

Make sure the Vite dev server is running (`npm run dev`). Check that `public/hot` file exists when Vite is running.

## Deployment

See [DEPLOYMENT.md](./DEPLOYMENT.md) for detailed deployment instructions to Heroku.

## Project Structure

- `app/` - Laravel application code
    - `Console/Commands/` - Custom artisan commands
    - `Http/Controllers/` - API and web controllers
    - `Models/` - Eloquent models
- `resources/js/` - React frontend code (Inertia)
    - `pages/` - Page components
    - `components/` - Reusable components
    - `lib/` - Utilities and helpers
- `routes/` - Route definitions
    - `api.php` - API routes
    - `web.php` - Web routes (admin panel)
- `database/` - Migrations and seeders

## Tech Stack

- **Backend:** Laravel 12, PHP 8.5
- **Frontend:** React 19, Inertia.js, TypeScript
- **Styling:** Tailwind CSS 4
- **Build Tool:** Vite 7
- **Database:** PostgreSQL (production), MySQL (local)
- **Hosting:** Heroku
- **CDN:** Cloudinary (image storage)
