<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * LoginController
 *
 * Handles user authentication (login/logout) functionality.
 * - create() and store() are protected by 'guest' middleware (only for non-authenticated users)
 * - destroy() is protected by 'auth' middleware (only for authenticated users)
 */
class LoginController extends Controller
{
    /**
     * Show the login form.
     *
     * @return View The login form view
     */
    public function create(): View
    {
        return view("auth.login");
    }

    /**
     * Handle a login request.
     *
     * 1. Validates the input (email, password)
     * 2. Attempts to authenticate the user using Auth::attempt()
     * 3. If successful, regenerates the session and redirects to home
     * 4. If failed, redirects back with an error message
     *
     * Auth::attempt() does the following:
     * - Finds a user by email
     * - Hashes the provided password and compares to stored hash
     * - If match, logs the user in (creates session) and returns true
     *
     * Session regeneration prevents "session fixation" attacks where
     * an attacker tricks you into using a session ID they already know.
     *
     * @param Request $request The incoming HTTP request
     * @return RedirectResponse Redirect to home or back to login with errors
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        if (Auth::attempt($credentials)) {
            // Regenerate session ID to prevent session fixation attacks
            $request->session()->regenerate();
            return redirect()->route("home");
        }

        // Authentication failed - redirect back with error
        return back()->withErrors([
            "email" => "Incorrect email or password",
        ]);
    }

    /**
     * Handle a logout request.
     *
     * 1. Logs the user out (clears auth state)
     * 2. Invalidates the session (destroys all session data)
     * 3. Regenerates CSRF token (prevents reuse of old forms)
     * 4. Redirects to home page
     *
     * @param Request $request The incoming HTTP request
     * @return RedirectResponse Redirect to home page
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        // Destroy the session completely
        $request->session()->invalidate();

        // Generate new CSRF token so old forms can't be submitted
        $request->session()->regenerateToken();

        return redirect()->route("home");
    }
}
