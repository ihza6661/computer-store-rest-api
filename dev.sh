#!/bin/bash

# Computer Store REST API - Development Server Starter
# This script starts both Laravel and Vite dev servers concurrently

echo "🚀 Starting Computer Store REST API Development Servers..."
echo ""
echo "📦 This will start:"
echo "   - Laravel Dev Server (http://localhost:8000)"
echo "   - Vite Dev Server (http://localhost:5173)"
echo ""
echo "Press Ctrl+C to stop both servers"
echo ""

# Check if npm dependencies are installed
if [ ! -d "node_modules" ]; then
    echo "⚠️  node_modules not found. Running npm install..."
    npm install
fi

# Start both servers using npx concurrently
npx concurrently \
    --names "LARAVEL,VITE" \
    --prefix-colors "cyan,magenta" \
    "php artisan serve" \
    "npm run dev"
