# Assignment 2

## Summary

This assignment connects the front-end CRUD application from Assignment 1 to a proper back-end with database storage using Laravel's MVC architecture. We replaced the hardcoded fake data with actual database operations using Eloquent ORM.

### What Was Built

- **Repository Model** - Eloquent model representing the resource with mass assignment protection
- **Database Migration** - Schema definition for the repositories table
- **Database Seeder** - Test data for development and testing
- **RepositoryController** - Handles all CRUD operations with validation
- **Updated Views** - Added required `dusk` attributes for testing and use named routes
- **Flash Messages** - Success messages after create/update/delete operations
- **Explicit Routes** - Each route defined individually with named routes (best practice)

---

## Key Concepts Explained

### MVC Architecture (Model-View-Controller)

MVC is a software design pattern that separates an application into three interconnected components:

1. **Model** - The data layer. Represents the structure of data, handles database operations (CRUD), and contains business logic. In Laravel, models use Eloquent ORM to interact with the database using PHP objects instead of raw SQL.

2. **View** - The presentation layer. Displays data to the user and handles the UI. In Laravel, views are Blade templates (`.blade.php` files) that contain HTML with embedded PHP directives.

3. **Controller** - The logic layer. Receives HTTP requests, processes them (validation, business logic), interacts with models, and returns responses (views or redirects). Controllers are the "glue" between models and views.

**Request Flow in Laravel:**
```
User Request → Router → Middleware → Controller → Model → Database
                                         ↓
User Response ← View ← Controller ← Model ← Database
```

### HTTP Methods and RESTful Routing

REST (Representational State Transfer) is an architectural style for designing web APIs. It uses standard HTTP methods to perform CRUD operations:

| HTTP Method | CRUD Operation | Purpose | Example Route |
|-------------|----------------|---------|---------------|
| GET | Read | Retrieve data (safe, no side effects) | `GET /repositories` |
| POST | Create | Create new resource | `POST /repositories` |
| PUT/PATCH | Update | Update existing resource (PUT = full, PATCH = partial) | `PUT /repositories/1` |
| DELETE | Delete | Remove resource | `DELETE /repositories/1` |

**Why use different HTTP methods?**
- **Semantic meaning**: Each method clearly indicates the operation's intent
- **Caching**: GET requests can be cached, POST/PUT/DELETE cannot
- **Safety**: GET and HEAD are "safe" (don't modify data), others are not
- **Idempotency**: GET, PUT, DELETE are idempotent (same result if called multiple times)

### HTTP Method Spoofing

**The Problem:** HTML forms only support two methods: `GET` and `POST`. There's no way to create a form that submits with `PUT`, `PATCH`, or `DELETE`:

```html
<!-- This doesn't work! Browsers ignore anything other than GET/POST -->
<form method="DELETE" action="/repositories/1">
```

**The Solution - Method Spoofing:** Laravel provides a workaround by using a hidden form field to "spoof" the actual method:

```html
<form method="POST" action="/repositories/1">
    @csrf
    @method('DELETE')  <!-- Creates: <input type="hidden" name="_method" value="DELETE"> -->
    <button type="submit">Delete</button>
</form>
```

**How it works:**
1. The form submits as `POST` (which browsers support)
2. Laravel's middleware reads the `_method` hidden field
3. Laravel routes the request as if it were a `DELETE` request
4. The `Route::delete()` handler receives the request

**Why we need this:**
- To follow REST conventions with proper HTTP semantics
- To use `Route::put()`, `Route::patch()`, and `Route::delete()` in Laravel
- To have meaningful, intention-revealing routes

### CSRF Protection (Cross-Site Request Forgery)

**What is CSRF?**
CSRF is an attack where a malicious website tricks a user's browser into making unwanted requests to another site where the user is authenticated.

**Example Attack:**
1. User logs into `bank.com` and has a session cookie
2. User visits `evil.com` which has: `<img src="https://bank.com/transfer?to=attacker&amount=1000">`
3. Browser sends the request with the user's bank cookies
4. Bank processes the transfer thinking it's legitimate

**How Laravel prevents CSRF:**
1. Laravel generates a unique token for each user session
2. Every form must include this token: `@csrf` creates `<input type="hidden" name="_token" value="...">`
3. When form is submitted, Laravel verifies the token matches the session
4. If token is missing or invalid, Laravel rejects the request with a 419 error

```html
<form method="POST" action="/repositories">
    @csrf  <!-- Required for all POST/PUT/PATCH/DELETE forms -->
    <!-- form fields -->
</form>
```

**Why it works:**
- The attacker's site cannot read the CSRF token (same-origin policy)
- Without the token, the malicious request is rejected
- Each token is unique per session and cannot be guessed

### Mass Assignment Protection

**What is Mass Assignment?**
Mass assignment is when you pass an array of data directly to create or update a model:

```php
// Mass assignment - all array keys become model attributes
Repository::create($request->all());  // Dangerous!
```

**The Vulnerability:**
An attacker could add extra fields to the form submission:
```html
<!-- Attacker adds this hidden field -->
<input type="hidden" name="is_admin" value="1">
```

If the model blindly accepts all input, the attacker could set `is_admin=1` and gain admin privileges.

**Laravel's Protection - $fillable:**
The `$fillable` property whitelists which fields can be mass-assigned:

```php
class Repository extends Model
{
    protected $fillable = ['name', 'url', 'description', 'guide'];
}
```

Now even if an attacker submits `is_admin=1`, it's ignored because `is_admin` isn't in `$fillable`.

**Alternative - $guarded:**
Instead of whitelisting, you can blacklist fields:
```php
protected $guarded = ['id', 'is_admin'];  // These fields cannot be mass-assigned
```

### Route Model Binding

**What is it?**
Route Model Binding automatically converts route parameters (like IDs) into model instances.

**Without Route Model Binding:**
```php
Route::get('/{id}', function ($id) {
    $repository = Repository::find($id);
    if (!$repository) {
        abort(404);
    }
    return view('show', ['repository' => $repository]);
});
```

**With Route Model Binding:**
```php
Route::get('/{repository}', function (Repository $repository) {
    return view('show', ['repository' => $repository]);
});
```

**How it works:**
1. Laravel sees the `{repository}` parameter and the `Repository $repository` type-hint
2. It automatically queries `Repository::findOrFail($id)`
3. If not found, it automatically returns a 404 error
4. If found, it injects the model instance into your controller method

**Benefits:**
- Less boilerplate code
- Automatic 404 handling
- Cleaner controller methods
- Consistent error handling

### Named Routes

**What are they?**
Named routes give a unique name to a route, allowing you to reference it by name instead of URL.

**Defining a named route:**
```php
Route::get('/repositories/{repository}/edit', [RepositoryController::class, 'edit'])
    ->name('repositories.edit');
```

**Using named routes in views:**
```php
// Instead of hardcoding:
<a href="/repositories/{{ $repository->id }}/edit">Edit</a>

// Use the route helper:
<a href="{{ route('repositories.edit', $repository) }}">Edit</a>
```

**Why use named routes?**
1. **URL changes don't break links**: If you change `/repositories` to `/repos`, you only update the route definition, not every link
2. **IDE support**: Autocompletion and error detection
3. **Cleaner code**: `route('home')` is more readable than `'/'`
4. **Parameter handling**: Laravel automatically builds URLs with parameters

### Flash Messages

**What are they?**
Flash messages are temporary data stored in the session for exactly one request. They're used to show feedback after an action.

**How they work:**
```php
// In controller - store message in session
return redirect()->route('home')->with('success', 'Entity added successfully');

// In view - display and automatically remove
@if (session('success'))
    <div class="alert">{{ session('success') }}</div>
@endif
```

**Why "flash"?**
The data "flashes" - it exists for one request only. After being read, it's automatically deleted from the session. This prevents the message from showing again on page refresh.

### Eloquent ORM

**What is ORM?**
ORM (Object-Relational Mapping) lets you interact with database tables using PHP objects instead of SQL queries.

**Without ORM (raw SQL):**
```php
$results = DB::select('SELECT * FROM repositories WHERE id = ?', [1]);
```

**With Eloquent ORM:**
```php
$repository = Repository::find(1);
$repository->name = 'New Name';
$repository->save();
```

**Key Eloquent methods:**
| Method | Purpose |
|--------|---------|
| `Model::all()` | Get all records |
| `Model::find($id)` | Find by primary key |
| `Model::create($data)` | Insert new record |
| `$model->update($data)` | Update existing record |
| `$model->delete()` | Delete record |
| `Model::where('column', 'value')->get()` | Query with conditions |

### Database Migrations

**What are they?**
Migrations are version control for your database schema. They define the structure of tables in PHP code.

**Why use migrations?**
1. **Version control**: Track schema changes in Git
2. **Team collaboration**: Everyone gets the same database structure
3. **Rollback**: Undo changes with `php artisan migrate:rollback`
4. **Environment parity**: Same schema in development, staging, production

**Example migration:**
```php
public function up(): void
{
    Schema::create('repositories', function (Blueprint $table) {
        $table->id();                    // Auto-incrementing primary key
        $table->string('name');          // VARCHAR(255)
        $table->string('url');           // VARCHAR(255)
        $table->string('description')->nullable();  // Can be NULL
        $table->text('guide');           // TEXT (long content)
        $table->timestamps();            // created_at, updated_at
    });
}
```

### Database Seeders

**What are they?**
Seeders populate your database with initial or test data.

**Why use seeders?**
1. **Development**: Start with realistic test data
2. **Testing**: Consistent data for automated tests
3. **Demo**: Populate demo environments
4. **Initial data**: Required records (admin user, default settings)

**Running seeders:**
```bash
php artisan db:seed              # Run all seeders
php artisan migrate:fresh --seed # Reset DB and seed
```

---

## Task 0: Data and Testing Configuration

### ✅ Create migration file for the repositories table
**How I did it:** 
I used the artisan CLI command `php artisan make:model Repository -ms` which creates a model along with a migration (`-m`) and seeder (`-s`) file. This is best practice because it ensures correct naming conventions and timestamps. Then I edited the migration file to add the required columns: `name` (string), `url` (string), `description` (nullable string), and `guide` (text). The migration uses Laravel's Schema Builder with Blueprint to define the table structure.

**File:** [database/migrations/2025_12_21_170344_create_repositories_table.php](../../portal-app/database/migrations/2025_12_21_170344_create_repositories_table.php)

### ✅ Create the Repository model
**How I did it:** 
The model was created with the same artisan command above. I then edited the model to add the `$fillable` array with the fields `['name', 'url', 'description', 'guide']`. This protects against mass-assignment vulnerabilities by explicitly whitelisting which fields can be filled via `Repository::create()` or `$repository->fill()`. The model also includes a comment explaining what a Model is in MVC architecture.

**File:** [app/Models/Repository.php](../../portal-app/app/Models/Repository.php)

### ✅ Create seeder file with test data
**How I did it:** 
The seeder was created with the artisan command. I edited it to create 3 test repositories using `Repository::create()`. Each repository has a name, url, description, and onboarding guide. I also registered the seeder in `DatabaseSeeder.php` by adding `$this->call(RepositorySeeder::class)` so it runs when executing `php artisan db:seed`.

**Files:** 
- [database/seeders/RepositorySeeder.php](../../portal-app/database/seeders/RepositorySeeder.php)
- [database/seeders/DatabaseSeeder.php](../../portal-app/database/seeders/DatabaseSeeder.php)

### ✅ Set ASSIGNMENT_RESOURCE in .env and .env.example
**How I did it:** 
I added `ASSIGNMENT_RESOURCE=Repository` to both `.env` and `.env.example` files. This environment variable is used by the Dusk tests to dynamically load the correct model class for testing.

**Files:** 
- [.env](../../portal-app/.env)
- [.env.example](../../portal-app/.env.example)

---

## Task 1: Index Page

### ✅ Index page is accessible at the root URL `/`
**How I did it:** 
I defined an explicit route in `web.php`: `Route::get('/', [RepositoryController::class, 'index'])->name('home')`. This maps the root URL to the `index` method of the controller and gives it the name `home` for use with the `route()` helper.

**File:** [routes/web.php](../../portal-app/routes/web.php)

### ✅ Index page displays all resources from the database
**How I did it:** 
The controller's `index()` method retrieves all repositories using `Repository::all()` and passes them to the view. The view loops through them with `@foreach ($repositories as $repository)`.

**Files:** 
- [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)
- [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

### ✅ Each resource element has `dusk="element"` attribute
**How I did it:** 
Added `dusk="element"` to each `<li>` in the repository list: `<li class="repo-list__item" dusk="element">`. The `dusk` attribute is used by Laravel Dusk for browser testing - it provides a stable selector that won't break if CSS classes change.

**File:** [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

### ✅ Link to create page has `dusk="to-create"` attribute
**How I did it:** 
Added `dusk="to-create"` to the "Add Repository" link in the index view.

**File:** [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

### ✅ Each element has links/buttons for: view, edit, delete
**How I did it:** 
Each repository item has: a View link with `dusk="to-show"`, an Edit link with `dusk="to-edit"`, and a Delete form/button with `dusk="to-delete"`. All use named routes: `route('repositories.show', $repository)`, `route('repositories.edit', $repository)`, `route('repositories.destroy', $repository)`.

**File:** [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

---

## Task 2: Create Page

### ✅ Create page is accessible via `/create` URL
**How I did it:** 
Defined the route: `Route::get('/create', [RepositoryController::class, 'create'])->name('repositories.create')`. The controller's `create()` method returns the create view.

**File:** [routes/web.php](../../portal-app/routes/web.php)

### ✅ Form action uses named route with POST method
**How I did it:** 
The form uses `action="{{ route('repositories.store') }}"` with `method="POST"`. The store route is defined as `Route::post('/', [RepositoryController::class, 'store'])->name('repositories.store')`. POST is the correct HTTP method for creating new resources.

**Files:**
- [resources/views/repositories/create.blade.php](../../portal-app/resources/views/repositories/create.blade.php)
- [routes/web.php](../../portal-app/routes/web.php)

### ✅ Form submission creates a new resource in the database
**How I did it:** 
The controller's `store()` method validates the input using `$request->validate()` and creates the repository using `Repository::create($validated)`. Validation rules ensure data integrity:
- `name`: required, string, max 255 characters
- `url`: required, valid URL format, max 255 characters  
- `description`: optional (nullable), string, max 255 characters
- `guide`: required, string (no max - TEXT column)

**File:** [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)

### ✅ After creation, user is redirected to index page
**How I did it:** 
After creating the repository, the controller returns `redirect()->route('home')->with('success', 'Entity added successfully')`. This uses a named route redirect with a flash message.

**File:** [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)

### ✅ Success message "Entity added successfully" is displayed with `dusk="success-msg"`
**How I did it:** 
The index view checks for a flash message with `@if (session('success'))` and displays it in a div with `dusk="success-msg"`. The message only appears once (flash behavior) and disappears on page refresh.

**File:** [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

---

## Task 3: Show Page

### ✅ Show page is accessible via `/{repository}` URL
**How I did it:** 
Defined the route: `Route::get('/{repository}', [RepositoryController::class, 'show'])->name('repositories.show')`. The `{repository}` parameter uses Route Model Binding - Laravel automatically queries `Repository::findOrFail($id)` and injects the model instance into the controller method. If not found, it returns a 404 automatically.

**File:** [routes/web.php](../../portal-app/routes/web.php)

### ✅ Page displays all information from the resource
**How I did it:** 
The show view displays all repository fields: name (as heading), url (as clickable link with `target="_blank"`), description, and guide. Uses Eloquent model syntax: `$repository->name`, `$repository->url`, etc.

**File:** [resources/views/repositories/show.blade.php](../../portal-app/resources/views/repositories/show.blade.php)

---

## Task 4: Update Page

### ✅ Edit page is accessible via `/{repository}/edit` URL
**How I did it:** 
Defined the route: `Route::get('/{repository}/edit', [RepositoryController::class, 'edit'])->name('repositories.edit')`. Uses Route Model Binding to auto-fetch the repository.

**File:** [routes/web.php](../../portal-app/routes/web.php)

### ✅ Edit link in index has `dusk="to-edit"` attribute
**How I did it:** 
Added `dusk="to-edit"` to the Edit link in the index view.

**File:** [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

### ✅ Form contains same fields as create page, prefilled with resource data
**How I did it:** 
Each input has a `value` attribute with the current data: `value="{{ $repository->name }}"`. The textarea contains `{{ $repository->guide }}`. This allows users to see and modify the existing values.

**File:** [resources/views/repositories/edit.blade.php](../../portal-app/resources/views/repositories/edit.blade.php)

### ✅ Form action uses named route with PUT method (using @method('PUT'))
**How I did it:** 
The form uses:
```html
<form action="{{ route('repositories.update', $repository) }}" method="POST">
    @csrf
    @method('PUT')
```

Since HTML forms only support GET and POST, we use **HTTP method spoofing**:
1. The form's actual method is POST (browser-supported)
2. `@method('PUT')` adds a hidden field: `<input type="hidden" name="_method" value="PUT">`
3. Laravel's middleware reads this field and routes the request to `Route::put()`

The update route is: `Route::put('/{repository}', [RepositoryController::class, 'update'])->name('repositories.update')`.

**Files:**
- [resources/views/repositories/edit.blade.php](../../portal-app/resources/views/repositories/edit.blade.php)
- [routes/web.php](../../portal-app/routes/web.php)

### ✅ After update, user is redirected to index page
**How I did it:** 
After updating, the controller returns `redirect()->route('home')->with('success', 'Entity updated successfully')`.

**File:** [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)

### ✅ Success message "Entity updated successfully" is displayed with `dusk="success-msg"`
**How I did it:** 
Same flash message mechanism as create - the index view displays the success message.

**File:** [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

---

## Task 5: Delete

### ✅ Delete action uses a form (not a link) with DELETE method
**How I did it:** 
Created a form with HTTP method spoofing:
```html
<form action="{{ route('repositories.destroy', $repository) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" dusk="to-delete">Delete</button>
</form>
```

**Why use a form instead of a link?**
1. Links can only make GET requests
2. DELETE operations should use the DELETE HTTP method (REST convention)
3. Forms allow us to include CSRF tokens for security
4. Using method spoofing, we can send DELETE requests

**File:** [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

### ✅ Form action uses named route with proper HTTP method spoofing
**How I did it:** 
The form uses `action="{{ route('repositories.destroy', $repository) }}"` with `@method('DELETE')`. The destroy route is: `Route::delete('/{repository}', [RepositoryController::class, 'destroy'])->name('repositories.destroy')`.

**Files:**
- [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)
- [routes/web.php](../../portal-app/routes/web.php)

### ✅ After deletion, user is redirected to index page
**How I did it:** 
After deleting, the controller returns `redirect()->route('home')->with('success', 'Entity deleted successfully')`.

**File:** [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)

### ✅ Deleted element is no longer present in the index
**How I did it:** 
The controller calls `$repository->delete()` which permanently removes the record from the database (hard delete). When the index page reloads, `Repository::all()` no longer includes the deleted item.

For "soft deletes" (marking as deleted but keeping in database), you would use Laravel's `SoftDeletes` trait.

**File:** [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)

### ✅ Success message "Entity deleted successfully" is displayed with `dusk="success-msg"`
**How I did it:** 
Same flash message mechanism as create and update.

**File:** [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)

---

## Additional Requirements

### ✅ Back-end data validation is implemented
**How I did it:** 
Both `store()` and `update()` methods validate input using `$request->validate()`:

```php
$validated = $request->validate([
    "name" => "required|string|max:255",
    "url" => "required|url|max:255",
    "description" => "nullable|string|max:255",
    "guide" => "required|string",
]);
```

**What each rule means:**
- `required` - Field must be present and not empty
- `string` - Must be a string type
- `max:255` - Maximum 255 characters
- `nullable` - Field can be null/empty
- `url` - Must be a valid URL format

**What happens when validation fails:**
1. Laravel automatically redirects back to the form
2. Error messages are flashed to the session
3. Old input is preserved so user doesn't have to re-type everything
4. The controller code after `validate()` never executes

**File:** [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)

### ✅ Named routes used throughout
**How I did it:** 
All routes have names (e.g., `->name('home')`, `->name('repositories.create')`). All views and controller redirects use `route()` helper instead of hardcoded URLs.

**Benefits:**
- If URLs change, only update route definition (not every link)
- IDE autocompletion and error detection
- Cleaner, more readable code
- Automatic URL generation with parameters

**Files:**
- [routes/web.php](../../portal-app/routes/web.php)
- [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php)
- [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php)
- [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php)

### ✅ All Dusk tests pass
**How I did it:** 
All 21 Dusk browser tests pass. To run: start the server with `php artisan serve` in one terminal, then run `php artisan dusk` in another. Tests use Brave browser (configured in `DuskTestCase.php`).

---

## Files Changed/Created

| File | Purpose |
|------|---------|
| [app/Models/Repository.php](../../portal-app/app/Models/Repository.php) | Eloquent model with $fillable and MVC explanation |
| [database/migrations/2025_12_21_170344_create_repositories_table.php](../../portal-app/database/migrations/2025_12_21_170344_create_repositories_table.php) | Database schema |
| [database/seeders/RepositorySeeder.php](../../portal-app/database/seeders/RepositorySeeder.php) | Test data |
| [database/seeders/DatabaseSeeder.php](../../portal-app/database/seeders/DatabaseSeeder.php) | Registers RepositorySeeder |
| [app/Http/Controllers/RepositoryController.php](../../portal-app/app/Http/Controllers/RepositoryController.php) | CRUD operations with validation |
| [routes/web.php](../../portal-app/routes/web.php) | Explicit routes with names |
| [resources/views/repositories/index.blade.php](../../portal-app/resources/views/repositories/index.blade.php) | Added dusk attributes, named routes |
| [resources/views/repositories/create.blade.php](../../portal-app/resources/views/repositories/create.blade.php) | Named route for form action |
| [resources/views/repositories/show.blade.php](../../portal-app/resources/views/repositories/show.blade.php) | Named routes, Eloquent syntax |
| [resources/views/repositories/edit.blade.php](../../portal-app/resources/views/repositories/edit.blade.php) | Named routes, Eloquent syntax |
| [resources/views/components/header.blade.php](../../portal-app/resources/views/components/header.blade.php) | Named routes |
| [.env](../../portal-app/.env) | Added ASSIGNMENT_RESOURCE=Repository |
| [.env.example](../../portal-app/.env.example) | Added ASSIGNMENT_RESOURCE=Repository |
| [tests/DuskTestCase.php](../../portal-app/tests/DuskTestCase.php) | Configured to use Brave browser |
| [tests/Browser/*.php](../../portal-app/tests/Browser/) | Dusk browser tests |
