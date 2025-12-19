<?php

use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
})->name("home");

Route::get("/repositories/create", function () {
    return view("repositories.create");
})->name("repositories.create");
