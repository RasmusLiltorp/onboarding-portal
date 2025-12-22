# Onboarding Portal - Web Development Course

This repository contains my solutions for the web development course assignments.

## Structure

```
onboarding-portal/
├── portal-app/              # Laravel application
├── exercise-answers/        # Assignment documentation
│   ├── Assignment 1/        # Front-end basics
│   ├── Assignment 2/        # Back-end CRUD with Laravel
│   └── Assignment 3/        # Authentication & Authorization
└── README.md
```

## Assignments

### Assignment 1: Front-End
Basic front-end structure with HTML, CSS, and Blade templates.

### Assignment 2: Back-End CRUD
- Connected front-end to database using Laravel MVC
- Implemented CRUD operations for repositories
- Database migrations and seeders
- Form validation
- All Dusk tests passing

### Assignment 3: Authentication & Authorization
- User registration, login, logout
- Authorization with `@auth`/`@guest` directives
- Route protection with `auth`/`guest` middleware
- Favorites feature (many-to-many relationship)

## Quick Start

```bash
cd portal-app
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

## Test Credentials

| Email | Password |
|-------|----------|
| user1@example.com | password |
| user2@example.com | password |
