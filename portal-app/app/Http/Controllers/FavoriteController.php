<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * FavoriteController
 *
 * Handles the favorites (wishlist) functionality.
 * Allows authenticated users to save repositories to their favorites list.
 * All routes in this controller are protected by the 'auth' middleware.
 *
 * This uses a many-to-many relationship between User and Repository,
 * stored in the 'repository_user' pivot table.
 */
class FavoriteController extends Controller
{
    /**
     * Display the authenticated user's favorite repositories.
     *
     * Uses the belongsToMany relationship defined in User model
     * to fetch all repositories the user has favorited.
     *
     * @return View The favorites index view
     */
    public function index(): View
    {
        // Get all repositories favorited by the current user
        $repositories = Auth::user()->repositories;
        return view("favorites.index", ["repositories" => $repositories]);
    }

    /**
     * Add a repository to the authenticated user's favorites.
     *
     * Uses the attach() method on the belongsToMany relationship
     * to create a new record in the pivot table (repository_user).
     *
     * attach() - adds the relationship (allows duplicates)
     * syncWithoutDetaching() - adds without duplicates
     * toggle() - adds if not exists, removes if exists
     *
     * @param Repository $repository The repository to add (route model binding)
     * @return RedirectResponse Redirect back to previous page
     */
    public function store(Repository $repository): RedirectResponse
    {
        // Add repository to user's favorites via pivot table
        Auth::user()->repositories()->attach($repository->id);
        return redirect()->back();
    }
}
