# Onboardo - Repository Onboarding Portal

A Laravel CRUD application for managing repository onboarding guides. Built as part of a web development course.

## Features

- **Repository Management** - Create, view, edit, and delete repositories with onboarding guides
- **User Authentication** - Register, login, and logout functionality
- **Authorization** - Protected routes using middleware (auth/guest)
- **Favorites** - Authenticated users can save repositories to their favorites list

## Requirements

- PHP 8.2+
- Composer
- Node.js & npm
- SQLite (or configure another database)

## Setup

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations and seed database
php artisan migrate:fresh --seed

# Build assets
npm run build
```

## Running the Application

```bash
# Start the development server
php artisan serve

# In another terminal, run Vite for hot-reload (optional)
npm run dev
```

Visit `http://localhost:8000` in your browser.

## Test Users

After seeding, two test users are available:

| Email | Password |
|-------|----------|
| user1@example.com | password |
| user2@example.com | password |

## Running Tests

```bash
# Start the server first
php artisan serve

# In another terminal, run Dusk tests
php artisan dusk
```

## Project Structure

```
app/
├── Http/Controllers/
│   ├── RepositoryController.php  # CRUD operations
│   ├── RegistrationController.php # User registration
│   ├── LoginController.php        # Login/logout
│   └── FavoriteController.php     # Favorites feature
├── Models/
│   ├── User.php                   # User model with favorites relationship
│   └── Repository.php             # Repository model
database/
├── migrations/                    # Database schema
└── seeders/                       # Test data
resources/views/
├── auth/                          # Login/register forms
├── repositories/                  # CRUD views
├── favorites/                     # Favorites list
├── components/                    # Reusable components (header)
└── layouts/                       # Base layout
routes/
└── web.php                        # All routes with middleware
```

## Routes

| Method | URI | Name | Middleware |
|--------|-----|------|------------|
| GET | / | home | - |
| GET | /register | registration.create | guest |
| POST | /register | registration.store | guest |
| GET | /login | login | guest |
| POST | /login | login.store | guest |
| POST | /logout | login.destroy | auth |
| GET | /favorites | registered.index | auth |
| POST | /favorites/{repository} | registered.store | auth |
| GET | /create | repositories.create | auth |
| POST | / | repositories.store | auth |
| GET | /{repository} | repositories.show | - |
| GET | /{repository}/edit | repositories.edit | auth |
| PUT | /{repository} | repositories.update | auth |
| DELETE | /{repository} | repositories.destroy | auth |
