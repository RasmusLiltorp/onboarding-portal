<?php

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

// Create - show create form
Route::get("/create", [RepositoryController::class, "create"])->name(
    "repositories.create",
);

// Store - save new repository (POST to root URL per test requirements)
Route::post("/", [RepositoryController::class, "store"])->name(
    "repositories.store",
);

// Show - display single repository
Route::get("/{repository}", [RepositoryController::class, "show"])->name(
    "repositories.show",
);

// Edit - show edit form
Route::get("/{repository}/edit", [RepositoryController::class, "edit"])->name(
    "repositories.edit",
);

// Update - update repository
Route::put("/{repository}", [RepositoryController::class, "update"])->name(
    "repositories.update",
);

// Destroy - delete repository
Route::delete("/{repository}", [RepositoryController::class, "destroy"])->name(
    "repositories.destroy",
);
