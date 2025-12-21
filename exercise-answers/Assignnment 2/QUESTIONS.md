## Question 1: CRUD Operations

### How is the data being handled from the front-end to the back-end and database, and back?

The data flow in our Laravel application follows this path:

**Front-end → Back-end → Database:**
1. User fills out a form in the browser (View layer - Blade template)
2. Form submits an HTTP request to a route (e.g., POST to `/`)
3. Laravel's router matches the URL and HTTP method to a controller action
4. The controller receives the request, validates the data using `$request->validate()`
5. If validation passes, the controller calls the Model (e.g., `Repository::create($validated)`)
6. Eloquent ORM translates this to an SQL INSERT/UPDATE query
7. The database stores the data

**Database → Back-end → Front-end:**
1. Controller calls the Model to fetch data (e.g., `Repository::all()`)
2. Eloquent ORM translates this to an SQL SELECT query
3. Database returns the raw data
4. Eloquent converts the data into PHP objects (Model instances)
5. Controller passes these objects to a View (e.g., `view('repositories.index', ['repositories' => $repositories])`)
6. Blade template renders the data as HTML
7. HTML response is sent to the browser

### What are the HTTP methods used in your application for each operation?

| Operation | HTTP Method | Route              | Controller Method |
|-----------|-------------|--------------------|--------------------|
| Create    | GET         | `/create`          | `create()`         |
| Store     | POST        | `/`                | `store()`          |
| Read All  | GET         | `/`                | `index()`          |
| Read One  | GET         | `/{repository}`    | `show()`           |
| Edit      | GET         | `/{repository}/edit` | `edit()`         |
| Update    | PUT         | `/{repository}`    | `update()`         |
| Delete    | DELETE      | `/{repository}`    | `destroy()`        |

### How did you handle the correct HTTP methods?

Browsers only support GET and POST natively in HTML forms. To use PUT and DELETE methods, we use **HTTP Method Spoofing**:

```html
<form method="POST" action="{{ route('repositories.update', $repository) }}">
    @csrf
    @method('PUT')
    <!-- form fields -->
</form>
```

- `method="POST"`: The actual HTTP method the browser sends
- `@method('PUT')`: A Blade directive that adds a hidden input `<input type="hidden" name="_method" value="PUT">`
- Laravel's middleware reads this `_method` field and treats the request as a PUT request

This allows us to follow RESTful conventions where:
- **PUT** is used for updates (replacing/modifying an existing resource)
- **DELETE** is used for removing resources

Without method spoofing, we would be forced to use POST for everything, losing the semantic meaning of different HTTP methods.

---
## Question 2: Models from MVC

### What is a Model in MVC?

A Model is the "M" in MVC (Model-View-Controller) architecture. It represents the **data layer** of the application and is responsible for:

1. **Defining data structure**: Which fields/attributes exist (name, url, description, guide)
2. **Database interaction**: Performing CRUD operations (Create, Read, Update, Delete)
3. **Relationships**: Defining how entities relate to each other (hasMany, belongsTo, etc.)
4. **Business logic**: Encapsulating rules and logic related to the data
5. **Data validation**: Ensuring data integrity (though Laravel often handles this in controllers)

The Model abstracts away the database implementation details. Instead of writing raw SQL, you work with PHP objects.

### What model(s) do you have in your application?

Our application has one custom model:

- **Repository** (`app/Models/Repository.php`): Represents a code repository with its onboarding information

Laravel also provides a default **User** model, but we don't use it in this application.

### How are these models related to the database?

Laravel uses **Eloquent ORM** (Object-Relational Mapping) to connect Models to database tables:

| Model Class | Database Table | Convention |
|-------------|----------------|------------|
| `Repository` | `repositories` | Laravel auto-pluralizes the model name |
| `User` | `users` | Same convention |

The connection is established by:
1. The model class extending `Illuminate\Database\Eloquent\Model`
2. Laravel's naming convention (model name → pluralized table name)
3. The database connection configured in `.env` and `config/database.php`

### How do you use this model to retrieve or update the resources from the database?

**Retrieving data:**
```php
// Get all repositories
$repositories = Repository::all();

// Find a specific repository by ID
$repository = Repository::find(1);

// Find or fail (returns 404 if not found)
$repository = Repository::findOrFail(1);

// Query with conditions
$repositories = Repository::where('name', 'like', '%laravel%')->get();
```

**Creating data:**
```php
// Mass assignment - uses $fillable for security
Repository::create([
    'name' => 'My Repo',
    'url' => 'https://github.com/example/repo',
    'description' => 'A sample repository',
    'guide' => 'Getting started guide...'
]);
```

**Updating data:**
```php
// Find and update
$repository = Repository::find(1);
$repository->update([
    'name' => 'Updated Name',
    'description' => 'Updated description'
]);
```

**Deleting data:**
```php
// Find and delete
$repository = Repository::find(1);
$repository->delete();
```

---

## Question 3: Data Validation

### Is JavaScript validation enough to have consistent resources in your database? Why yes/no?

No, JavaScript validation alone is not enough to ensure consistent resources in the database.

Client-side validation can be bypassed in several ways:
- Users can disable JavaScript in their browser
- Users can use browser developer tools to modify or remove validation
- Users can send HTTP requests directly using tools like cURL or Postman
- Users can manipulate the DOM to change form behavior
- Malicious actors can craft custom requests that skip the front-end entirely

JavaScript validation provides a good user experience (immediate feedback), but it cannot be trusted for data integrity or security.

### Independently of the answer to the previous question, would you validate data both at the front-end and back-end side? Why yes/no?

Yes, we should validate data on both frontend and backend:

**Frontend validation benefits:**
- Immediate feedback to users (no server round-trip)
- Better user experience
- Reduces unnecessary server load
- Catches simple errors quickly (empty fields, format issues)

**Backend validation benefits:**
- Cannot be bypassed - it's the last line of defense
- Ensures data integrity in the database
- Protects against malicious input
- Validates against current server state (e.g., unique email checks)

In our Laravel application, we implement backend validation in the controller:
```php
$validated = $request->validate([
    'name' => 'required|string|max:255',
    'url' => 'required|url|max:255',
    'description' => 'nullable|string|max:255',
    'guide' => 'required|string',
]);
```

This ensures that even if someone bypasses the frontend, the data must still pass validation before being stored in the database.
