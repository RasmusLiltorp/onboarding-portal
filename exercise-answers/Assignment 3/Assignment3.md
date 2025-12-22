# Assignment 3

## Summary

This assignment adds authentication and authorization to the CRUD application from Assignment 2. Users can register, log in, and log out. Different features are visible/accessible depending on whether the user is authenticated. A personalization feature (Favorites) allows authenticated users to save specific repositories.

### What Was Built

- **User Authentication** - Registration, login, and logout functionality
- **Authorization (View)** - Show/hide UI elements based on auth state using `@auth` and `@guest` Blade directives
- **Authorization (Controller)** - Restrict routes using `auth` and `guest` middleware
- **Favorites Feature** - Users can add repositories to their favorites list (many-to-many relationship)
- **Protected CRUD** - Create, edit, update, delete operations now require authentication

---

## Key Concepts Explained

### Authentication vs Authorization

**Authentication** answers: "Who are you?"
- Verifying identity via email/password
- Creating a session after successful login
- Logging out (destroying the session)

**Authorization** answers: "What can you do?"
- Checking if the user has permission to perform an action
- Restricting routes to authenticated or guest users
- Hiding/showing UI elements based on auth state

### Sessions and Cookies

How Laravel authentication works:
1. User submits login form with email/password
2. Server verifies credentials (password is hashed with bcrypt)
3. Server creates a session (stored in files by default)
4. Server sends a session cookie to the browser
5. Browser includes cookie with every subsequent request
6. Server reads cookie, finds session, knows who the user is

### Password Hashing

Never store plain-text passwords. Laravel automatically hashes passwords when the User model has `'password' => 'hashed'` in its `casts` array. This uses bcrypt by default.

```php
// When creating a user, just pass the plain password
User::create(['password' => 'mypassword']);
// Laravel automatically hashes it before storing

// When verifying (login), use Auth::attempt()
Auth::attempt(['email' => $email, 'password' => $password]);
// Laravel hashes the input and compares to stored hash
```

---

## Task 0: Data and Testing Configuration

### [x] Create/update migration files for users table
**How I did it:**
Laravel already includes a users migration by default at `database/migrations/0001_01_01_000000_create_users_table.php`. It has all required fields: id, name, email, password, timestamps.

**File:** [database/migrations/0001_01_01_000000_create_users_table.php](../../portal-app/database/migrations/0001_01_01_000000_create_users_table.php)

### [x] Create pivot table migration for user-repository relationship
**How I did it:**
Ran `php artisan make:migration create_repository_user_table` to create the pivot table. Added foreign keys for `user_id` and `repository_id` with cascade delete. The table name follows Laravel convention: singular model names in alphabetical order joined by underscore.

**What is a pivot table?**
A pivot table is a third table that sits between two other tables to create a many-to-many relationship. 

**The problem it solves:**
- A user can register many repositories (wishlist)
- A repository can be registered by many users
- You can't store this in either the `users` or `repositories` table alone

**The solution:**
Create a third table (`repository_user`) that only stores pairs of IDs - linking which user registered which repository:

```
users                 repository_user              repositories
+----+-------+        +----+---------+---------------+        +----+-----------+
| id | name  |        | id | user_id | repository_id |        | id | name      |
+----+-------+        +----+---------+---------------+        +----+-----------+
| 1  | John  |------->| 1  | 1       | 2             |<-------| 1  | laravel   |
| 2  | Jane  |        | 2  | 1       | 3             |        | 2  | api       |
+----+-------+        | 3  | 2       | 2             |        | 3  | frontend  |
                      +----+---------+---------------+        +----+-----------+

This means: John registered api and frontend. Jane registered api.
```

**File:** [database/migrations/2025_12_22_102606_create_repository_user_table.php](../../portal-app/database/migrations/2025_12_22_102606_create_repository_user_table.php)

### [x] Create user seeder with at least 2 users (password: "password")
**How I did it:**
Updated `DatabaseSeeder.php` to create 2 users using the User factory. Both users have password "password" (Laravel's User model automatically hashes passwords via the `casts` array).

**File:** [database/seeders/DatabaseSeeder.php](../../portal-app/database/seeders/DatabaseSeeder.php)

### [x] Ensure at least 4 resources in database via seeder
**How I did it:**
Added a 4th repository to `RepositorySeeder.php` (mobile-app). Ran `php artisan migrate:fresh --seed` to reset and seed the database.

**File:** [database/seeders/RepositorySeeder.php](../../portal-app/database/seeders/RepositorySeeder.php)

---

## Task 1: Registration

### [x] Header contains link to registration page with text "Sign Up"
**How I did it:**
Added `@guest` block in header.blade.php with a link to `route('registration.create')`. The `@guest` directive only shows content to non-authenticated users.

**File:** [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)

### [x] Registration form has fields: name, email, password
**How I did it:**
Created `resources/views/auth/register.blade.php` with a form containing three inputs: name (text), email (email type), password (password type). Each input has `name` attribute matching what the controller expects.

**File:** [resources/views/auth/register.blade.php](../../portal-app/resources/views/auth/register.blade.php)

### [x] Submit button has text "Submit"
**How I did it:**
Used `<input type="submit" value="Submit">` at the end of the form.

**File:** [resources/views/auth/register.blade.php](../../portal-app/resources/views/auth/register.blade.php)

### [x] Route name is `registration.create` (GET) and `registration.store` (POST)
**How I did it:**
Added two explicit routes in web.php:
- `Route::get("/register", ...)->name("registration.create")` - shows the form
- `Route::post("/register", ...)->name("registration.store")` - processes the form

**File:** [routes/web.php](../../portal-app/routes/web.php)

### [x] After registration, user is logged in and redirected to index
**How I did it:**
In the controller's `store()` method:
1. Validate the input with `$request->validate()`
2. Create the user with `User::create($validated)` - password is auto-hashed by the User model's `casts` array
3. Log them in with `Auth::login($user)`
4. Redirect to home with `redirect()->route('home')`

**File:** [app/Http/Controllers/RegistrationController.php](../../portal-app/app/Http/Controllers/RegistrationController.php)

### [x] Header displays the new user's name
**How I did it:**
Added `@auth` block in header.blade.php with `{{ Auth::user()->name }}`. The `@auth` directive only shows content to authenticated users.

**File:** [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)

---

## Task 2: Log In

### [x] Header contains link to login page with text "Log In"
**How I did it:**
Added a link inside the `@guest` block in header.blade.php pointing to `route('login')`.

**File:** [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)

### [x] Login form has fields: email, password
**How I did it:**
Created `resources/views/auth/login.blade.php` with a form containing email and password inputs.

**File:** [resources/views/auth/login.blade.php](../../portal-app/resources/views/auth/login.blade.php)

### [x] Route name is `login` (GET) and `login.store` (POST)
**How I did it:**
Added two routes in web.php:
- `Route::get("/login", ...)->name("login")` - shows the form
- `Route::post("/login", ...)->name("login.store")` - processes the form

**File:** [routes/web.php](../../portal-app/routes/web.php)

### [x] After login, user is redirected to index with name in header
**How I did it:**
In the controller's `store()` method, after `Auth::attempt($credentials)` succeeds, we regenerate the session and redirect to home. The header already shows `Auth::user()->name` inside `@auth` block.

**What is `Auth::attempt()`?**
`Auth::attempt()` checks the credentials against the database. It:
1. Finds a user by email
2. Hashes the provided password and compares it to the stored hash
3. If match, logs the user in (creates session) and returns true
4. If no match, returns false

**What is `$request->session()->regenerate()`?**
This creates a new session ID after login. It prevents "session fixation" attacks where an attacker tricks you into using a session ID they already know.

**File:** [app/Http/Controllers/LoginController.php](../../portal-app/app/Http/Controllers/LoginController.php)

### [x] If credentials are incorrect, show message "Incorrect email or password"
**How I did it:**
If `Auth::attempt()` returns false, we use `back()->withErrors(['email' => 'Incorrect email or password'])` to redirect back with an error. In the view, `@error('email')` displays the message.

**File:** [app/Http/Controllers/LoginController.php](../../portal-app/app/Http/Controllers/LoginController.php)

---

## Task 3: Log Out

### [x] Header contains logout form with button text "Log Out"
**How I did it:**
Added a form inside the `@auth` block in header.blade.php with a submit button.

**File:** [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)

### [x] Form has `dusk="logout"` attribute and uses POST method
**How I did it:**
The form has `method="POST"` and `dusk="logout"` attributes. We use POST (not GET) because logout changes server state (destroys session) - GET requests should be safe and not have side effects.

**File:** [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)

### [x] Route name is `login.destroy`
**How I did it:**
Added route: `Route::post("/logout", [LoginController::class, "destroy"])->name("login.destroy")`

**File:** [routes/web.php](../../portal-app/routes/web.php)

### [x] After logout, user is redirected to index page
**How I did it:**
In the controller's `destroy()` method:
1. `Auth::logout()` - logs out the user
2. `$request->session()->invalidate()` - destroys the session data
3. `$request->session()->regenerateToken()` - generates new CSRF token (prevents reuse of old token)
4. `redirect()->route("home")` - sends user to index

**Why invalidate session and regenerate token?**
Security best practice. After logout, the old session should be completely destroyed so it can't be hijacked. The CSRF token is regenerated so old forms can't be submitted.

**File:** [app/Http/Controllers/LoginController.php](../../portal-app/app/Http/Controllers/LoginController.php)

---

## Task 4: Authorization - View

### [x] Registration and Login links only visible to guest users
**How I did it:**
Wrapped the Sign Up and Log In links in `@guest ... @endguest` block. This Blade directive only renders content for non-authenticated users.

**File:** [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)

### [x] Logout button and user name only visible to authenticated users
**How I did it:**
Wrapped the user name and logout form in `@auth ... @endauth` block. This Blade directive only renders content for authenticated users.

**What are `@auth` and `@guest`?**
Blade directives that conditionally render content based on authentication state:
- `@auth` - only shows if user is logged in
- `@guest` - only shows if user is NOT logged in

They're shortcuts for `@if(Auth::check())` and `@if(!Auth::check())`.

**File:** [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)

---

## Task 5: Authorization - Controller

### [x] Apply `guest` middleware to registration routes (`registration.create`, `registration.store`)
### [x] Apply `guest` middleware to login routes (`login`, `login.store`)
**How I did it:**
Wrapped all registration and login routes in a `Route::middleware("guest")->group()` block.

**File:** [routes/web.php](../../portal-app/routes/web.php)

### [x] Apply `auth` middleware to logout route (`login.destroy`)
**How I did it:**
Wrapped the logout route in a `Route::middleware("auth")->group()` block.

**What is middleware?**
Middleware is code that runs BEFORE your controller. It can:
- Check conditions (is user logged in?)
- Modify the request
- Reject the request entirely (redirect or return error)

**What do `auth` and `guest` middleware do?**
- `auth` - If user is NOT logged in, redirects to login page
- `guest` - If user IS logged in, redirects to home page

This prevents:
- Guests from accessing protected routes (like logout)
- Authenticated users from accessing guest-only routes (like login/register - why would a logged-in user need to log in again?)

**File:** [routes/web.php](../../portal-app/routes/web.php)

---

## Task 6: Personalization (Favorites)

### [x] Add "Add to Favorites" button on show page (only for authenticated users)
**How I did it:**
Added a form inside `@auth` block in show.blade.php with a submit button.

**File:** [resources/views/repositories/show.blade.php](../../portal-app/resources/views/repositories/show.blade.php)

### [x] Form has `dusk="resource-registration"` attribute and POST method
**How I did it:**
The form has `method="POST"` and `dusk="resource-registration"` attributes as required by tests.

**File:** [resources/views/repositories/show.blade.php](../../portal-app/resources/views/repositories/show.blade.php)

### [x] Button action only accessible by authenticated users
**How I did it:**
The route `registered.store` is inside the `Route::middleware("auth")` group, so only authenticated users can access it.

**File:** [routes/web.php](../../portal-app/routes/web.php)

### [x] Add "My Favorites" link in header (only for authenticated users)
**How I did it:**
Added a link inside `@auth` block pointing to `route('registered.index')`.

**File:** [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)

### [x] Route name is `registered.index`
**How I did it:**
Added route: `Route::get("/favorites", ...)->name("registered.index")` inside the auth middleware group.

**File:** [routes/web.php](../../portal-app/routes/web.php)

### [x] Page displays names of user's registered resources
**How I did it:**
Created `favorites/index.blade.php` that loops through `$repositories` and displays each name.

**File:** [resources/views/favorites/index.blade.php](../../portal-app/resources/views/favorites/index.blade.php)

### [x] Define belongsToMany relationship in User and Repository models
**How I did it:**
Added `repositories()` method to User model and `users()` method to Repository model. Both return `$this->belongsToMany()`.

**What is `belongsToMany`?**
Defines a many-to-many relationship. Laravel automatically looks for a pivot table named `repository_user` (alphabetical order of model names). The method returns a relationship that lets you:
- `$user->repositories` - get all repositories favorited by user
- `$user->repositories()->attach($id)` - add a favorite
- `$user->repositories()->detach($id)` - remove a favorite

**Files:** 
- [app/Models/User.php](../../portal-app/app/Models/User.php)
- [app/Models/Repository.php](../../portal-app/app/Models/Repository.php)

---

## Files Changed/Created

| File | Purpose |
|------|---------|
| [database/migrations/2025_12_22_102606_create_repository_user_table.php](../../portal-app/database/migrations/2025_12_22_102606_create_repository_user_table.php) | Pivot table for user-repository favorites |
| [database/seeders/DatabaseSeeder.php](../../portal-app/database/seeders/DatabaseSeeder.php) | Seeds 2 test users with password "password" |
| [database/seeders/RepositorySeeder.php](../../portal-app/database/seeders/RepositorySeeder.php) | Seeds 4 test repositories |
| [app/Http/Controllers/RegistrationController.php](../../portal-app/app/Http/Controllers/RegistrationController.php) | Handles user registration |
| [app/Http/Controllers/LoginController.php](../../portal-app/app/Http/Controllers/LoginController.php) | Handles login and logout |
| [app/Http/Controllers/FavoriteController.php](../../portal-app/app/Http/Controllers/FavoriteController.php) | Handles adding/viewing favorites |
| [app/Models/User.php](../../portal-app/app/Models/User.php) | Added `repositories()` relationship |
| [app/Models/Repository.php](../../portal-app/app/Models/Repository.php) | Added `users()` relationship |
| [routes/web.php](../../portal-app/routes/web.php) | Auth routes with middleware groups |
| [resources/views/auth/register.blade.php](../../portal-app/resources/views/auth/register.blade.php) | Registration form |
| [resources/views/auth/login.blade.php](../../portal-app/resources/views/auth/login.blade.php) | Login form |
| [resources/views/favorites/index.blade.php](../../portal-app/resources/views/favorites/index.blade.php) | User's favorites list |
| [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php) | Auth-based navigation links |
| [resources/views/repositories/show.blade.php](../../portal-app/resources/views/repositories/show.blade.php) | Added favorites button |
