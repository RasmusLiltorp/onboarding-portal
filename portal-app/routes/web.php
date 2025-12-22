<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RepositoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routes for the Repository CRUD application with authentication.
|
| Middleware:
| - 'guest': Only non-authenticated users can access (redirects to home if logged in)
| - 'auth': Only authenticated users can access (redirects to login if not logged in)
|
| Route Model Binding: {repository} automatically resolves to a Repository model.
| Laravel queries Repository::findOrFail($id) and injects the model instance.
|
*/

// ==========================================================================
// Public Routes (no authentication required)
// ==========================================================================

// Index - list all repositories (accessible to everyone)
Route::get("/", [RepositoryController::class, "index"])->name("home");

// Show - display single repository (accessible to everyone)
// Note: This must be at the bottom to avoid catching other routes like /login
Route::get("/{repository}", [RepositoryController::class, "show"])->name(
    "repositories.show",
);

// ==========================================================================
// Guest Only Routes (only non-authenticated users)
// ==========================================================================
// These routes are for users who are NOT logged in.
// If a logged-in user tries to access these, they get redirected to home.

Route::middleware("guest")->group(function () {
    // Registration - create new user account
    Route::get("/register", [RegistrationController::class, "create"])->name(
        "registration.create",
    );
    Route::post("/register", [RegistrationController::class, "store"])->name(
        "registration.store",
    );

    // Login - authenticate existing user
    Route::get("/login", [LoginController::class, "create"])->name("login");
    Route::post("/login", [LoginController::class, "store"])->name(
        "login.store",
    );
});

// ==========================================================================
// Authenticated Only Routes (only logged-in users)
// ==========================================================================
// These routes require the user to be logged in.
// If a guest tries to access these, they get redirected to login.

Route::middleware("auth")->group(function () {
    // Logout - end user session
    Route::post("/logout", [LoginController::class, "destroy"])->name(
        "login.destroy",
    );

    // Favorites - user's saved repositories (many-to-many relationship)
    Route::get("/favorites", [FavoriteController::class, "index"])->name(
        "registered.index",
    );
    Route::post("/favorites/{repository}", [
        FavoriteController::class,
        "store",
    ])->name("registered.store");

    // Repository CRUD (protected - only authenticated users can create/edit/delete)
    Route::get("/create", [RepositoryController::class, "create"])->name(
        "repositories.create",
    );
    Route::post("/", [RepositoryController::class, "store"])->name(
        "repositories.store",
    );
    Route::get("/{repository}/edit", [
        RepositoryController::class,
        "edit",
    ])->name("repositories.edit");
    Route::put("/{repository}", [RepositoryController::class, "update"])->name(
        "repositories.update",
    );
    Route::delete("/{repository}", [
        RepositoryController::class,
        "destroy",
    ])->name("repositories.destroy");
});
