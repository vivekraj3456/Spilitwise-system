# Expense Splitter

Expense Splitter is a Laravel 12 app for tracking shared group expenses, balances, and suggested settlements.

## Features
- Create groups and invite members
- Record equal-split expenses
- View balances per group and a dashboard summary
- Recent activity and settlement suggestions

## Requirements
- PHP 8.2+
- Composer
- Node.js 18+ (npm or pnpm)
- A database (SQLite, MySQL, or Postgres)

## Setup
1. Copy the environment file:
   - `cp .env.example .env`
2. Install PHP dependencies:
   - `composer install`
3. Generate the app key:
   - `php artisan key:generate`
4. Run migrations:
   - `php artisan migrate`
5. Install frontend dependencies:
   - `npm install`
6. Build assets (or use the dev server):
   - `npm run dev`

## Run locally
- `php artisan serve`
- `npm run dev`

## Tests
- `php artisan test`

## Composer scripts
- `composer run setup` sets up the project end-to-end
- `composer run dev` starts the server, queue worker, log tailing, and Vite
