<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Main web routes. Additional route files are loaded below for organization.
|
*/

// Landing page
Route::get("/", function () {
    return view("welcome");
})->name("home");

// Load repository routes with prefix and name prefix
Route::prefix("repositories")
    ->name("repositories.")
    ->group(base_path("routes/repositories.php"));
