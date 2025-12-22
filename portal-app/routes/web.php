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
| Routes for the Repository CRUD application.
| Each route is explicitly defined with a named route for URL generation.
|
| Route Model Binding: {repository} automatically resolves to a Repository model.
|
*/

// Index - list all repositories
Route::get("/", [RepositoryController::class, "index"])->name("home");

// Guest only routes
Route::middleware("guest")->group(function () {
    // Registration
    Route::get("/register", [RegistrationController::class, "create"])->name(
        "registration.create",
    );
    Route::post("/register", [RegistrationController::class, "store"])->name(
        "registration.store",
    );

    // Login
    Route::get("/login", [LoginController::class, "create"])->name("login");
    Route::post("/login", [LoginController::class, "store"])->name(
        "login.store",
    );
});

// Auth only routes
Route::middleware("auth")->group(function () {
    // Logout
    Route::post("/logout", [LoginController::class, "destroy"])->name(
        "login.destroy",
    );

    // Favorites
    Route::get("/favorites", [FavoriteController::class, "index"])->name(
        "registered.index",
    );
    Route::post("/favorites/{repository}", [
        FavoriteController::class,
        "store",
    ])->name("registered.store");

    // Create - show create form
    Route::get("/create", [RepositoryController::class, "create"])->name(
        "repositories.create",
    );

    // Store - save new repository (POST to root URL per test requirements)
    Route::post("/", [RepositoryController::class, "store"])->name(
        "repositories.store",
    );

    // Edit - show edit form
    Route::get("/{repository}/edit", [
        RepositoryController::class,
        "edit",
    ])->name("repositories.edit");

    // Update - update repository
    Route::put("/{repository}", [RepositoryController::class, "update"])->name(
        "repositories.update",
    );

    // Destroy - delete repository
    Route::delete("/{repository}", [
        RepositoryController::class,
        "destroy",
    ])->name("repositories.destroy");
});

// Show - display single repository (public)
Route::get("/{repository}", [RepositoryController::class, "show"])->name(
    "repositories.show",
);
