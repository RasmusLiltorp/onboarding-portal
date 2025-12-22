<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * RegistrationController
 *
 * Handles user registration (sign up) functionality.
 * This controller is protected by the 'guest' middleware,
 * meaning only non-authenticated users can access these routes.
 */
class RegistrationController extends Controller
{
    /**
     * Show the registration form.
     *
     * @return View The registration form view
     */
    public function create(): View
    {
        return view("auth.register");
    }

    /**
     * Handle a registration request.
     *
     * 1. Validates the input (name, email, password)
     * 2. Creates a new user in the database
     * 3. Logs the user in automatically
     * 4. Redirects to the home page
     *
     * Note: Password is automatically hashed by Laravel because
     * the User model has 'password' => 'hashed' in its $casts array.
     *
     * @param Request $request The incoming HTTP request
     * @return RedirectResponse Redirect to home page
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "email" => "required|email|unique:users",
            "password" => "required|min:8",
        ]);

        // Create user - password is auto-hashed via User model's $casts
        $user = User::create($validated);

        // Log the user in immediately after registration
        Auth::login($user);

        return redirect()->route("home");
    }
}
