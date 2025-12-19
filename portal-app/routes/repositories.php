<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Repository Routes
|--------------------------------------------------------------------------
|
| Routes for managing repositories - viewing, creating, editing, deleting.
| All routes are prefixed with /repositories and named repositories.*
|
*/

// Fake data for now - will be replaced with database later
$repositories = [
    1 => [
        "id" => 1,
        "name" => "laravel-app",
        "url" => "https://github.com/example/laravel-app",
        "description" => "Main Laravel application",
        "guide" =>
            "Welcome to the Laravel App! Start by running composer install, then copy .env.example to .env and run php artisan key:generate.",
    ],
    2 => [
        "id" => 2,
        "name" => "api-service",
        "url" => "https://github.com/example/api-service",
        "description" => "REST API service",
        "guide" =>
            "This is our API service. Check the README for endpoint documentation.",
    ],
    3 => [
        "id" => 3,
        "name" => "frontend",
        "url" => "https://github.com/example/frontend",
        "description" => "React frontend",
        "guide" =>
            "Run npm install followed by npm run dev to start the development server.",
    ],
];

// List all repositories
Route::get("/", function () use ($repositories) {
    return view("repositories.index", ["repositories" => $repositories]);
})->name("index");

// Show create form
Route::get("/create", function () {
    return view("repositories.create");
})->name("create");

// Show single repository
Route::get("/{id}", function ($id) use ($repositories) {
    return view("repositories.show", [
        "repository" => $repositories[$id] ?? null,
    ]);
})->name("show");

// Show edit form
Route::get("/{id}/edit", function ($id) use ($repositories) {
    return view("repositories.edit", [
        "repository" => $repositories[$id] ?? null,
    ]);
})->name("edit");

// Store new repository (placeholder)
Route::post("/", function () {
    // TODO: Save to database
    return redirect()->route("repositories.index");
})->name("store");

// Update repository (placeholder)
Route::put("/{id}", function ($id) {
    // TODO: Update in database
    return redirect()->route("repositories.show", $id);
})->name("update");

// Delete repository (placeholder)
Route::delete("/{id}", function ($id) {
    // TODO: Delete from database
    return redirect()->route("repositories.index");
})->name("destroy");
