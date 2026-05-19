# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
# Development (runs Laravel server, queue worker, log tail, and Vite dev server concurrently)
composer dev

# Frontend only
npm run dev        # Vite dev server
npm run build      # Production build

# Testing
php artisan test                          # All tests via artisan
./vendor/bin/phpunit                      # All tests via PHPUnit directly
php artisan test --filter=TestClassName   # Single test class
./vendor/bin/phpunit --filter=test_method_name  # Single test method

# Linting
./vendor/bin/pint                         # Fix PHP code style
./vendor/bin/pint --test                  # Check without fixing

# Database
php artisan migrate
php artisan migrate:fresh --seed
php artisan db:seed
```

## Architecture

**Stack:** Laravel 11 (PHP 8.2+), MySQL, Blade templates, Vite, Tailwind CSS.

**Request flow:** `public/index.php` → `bootstrap/app.php` → `routes/web.php` → `app/Http/Controllers/`

**Frontend pipeline:** `resources/css/app.css` + `resources/js/app.js` → Vite → `public/build/`

**Database:** MySQL, database name `movies`. Session, cache, and queue are all database-backed (`sessions`, `cache`, `jobs` tables).

**Environment:** `.env` is active config; `.env.example` is the committed template. Current setup uses `APP_ENV=local`, MySQL on `127.0.0.1:3306`, credentials `root`/`admin`.

**Testing:** Feature tests in `tests/Feature/`, unit tests in `tests/Unit/`. PHPUnit config in `phpunit.xml`. Tests use an in-memory SQLite DB by default (see `phpunit.xml` env overrides).

-------------------------

We are going to create/copy this website: https://dizilah.com/ 

We will do scrapping, copies, etc
