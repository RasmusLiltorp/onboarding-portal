<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * RepositoryController
 *
 * A Controller is the "C" in MVC (Model-View-Controller) architecture.
 * It is responsible for handling incoming HTTP requests and returning responses.
 *
 * Request flow: Router → Middleware → Controller → Response
 *
 * The controller's job is to:
 * 1. Receive the HTTP request (after it passes through middleware)
 * 2. Process the request (validation, business logic)
 * 3. Interact with Models to fetch or persist data
 * 4. Return a response (typically a View or redirect)
 *
 * Controllers should be thin - they orchestrate the flow but delegate
 * heavy logic to Models, Services, or other classes.
 *
 * This controller handles all CRUD operations for the Repository resource,
 * following RESTful conventions with proper HTTP methods:
 *
 * | Method | URI              | Action  | Description                    |
 * |--------|------------------|---------|--------------------------------|
 * | GET    | /                | index   | Display all repositories       |
 * | GET    | /create          | create  | Show form to create new repo   |
 * | POST   | /create          | store   | Save new repository to DB      |
 * | GET    | /{repository}    | show    | Display a single repository    |
 * | GET    | /{repository}/edit | edit  | Show form to edit repository   |
 * | PUT    | /{repository}    | update  | Update repository in DB        |
 * | DELETE | /{repository}    | destroy | Remove repository from DB      |
 */
class RepositoryController extends Controller
{
    /**
     * Display a listing of all repositories.
     *
     * This is the index page (homepage) showing all repositories from the database.
     * Each repository will be displayed with links to view, edit, and delete.
     *
     * @return View The index view with all repositories
     */
    public function index(): View
    {
        // Retrieve all repositories from the database
        // Using all() is fine for small datasets; for larger datasets,
        // consider using paginate() for better performance
        $repositories = Repository::all();

        // Return the view with repositories data
        // The 'repositories.index' corresponds to resources/views/repositories/index.blade.php
        return view("repositories.index", [
            "repositories" => $repositories,
        ]);
    }

    /**
     * Show the form for creating a new repository.
     *
     * This displays an empty form where users can enter details
     * for a new repository (name, url, description, guide).
     *
     * @return View The create form view
     */
    public function create(): View
    {
        return view("repositories.create");
    }

    /**
     * Store a newly created repository in the database.
     *
     * This method handles the POST request from the create form.
     * It validates the input data and creates a new repository record.
     *
     * @param Request $request The incoming HTTP request with form data
     * @return RedirectResponse Redirects to index with success message
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        // Laravel will automatically redirect back with errors if validation fails
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "url" => "required|url|max:255",
            "description" => "nullable|string|max:255",
            "guide" => "required|string",
        ]);

        // Create a new repository using mass assignment
        // Only fields in the $fillable array of the model will be saved
        Repository::create($validated);

        // Redirect to the index page with a flash message
        // The 'with' method stores data in the session for one request
        // This message will be displayed using dusk="success-msg"
        return redirect()
            ->route("home")
            ->with("success", "Entity added successfully");
    }

    /**
     * Display the specified repository.
     *
     * Shows all details of a single repository including its onboarding guide.
     * Uses Route Model Binding to automatically fetch the repository by ID.
     *
     * @param Repository $repository The repository instance (auto-injected by Laravel)
     * @return View The show view with repository details
     */
    public function show(Repository $repository): View
    {
        // Laravel's Route Model Binding automatically fetches the repository
        // by its ID from the URL. If not found, it returns a 404 error.
        return view("repositories.show", [
            "repository" => $repository,
        ]);
    }

    /**
     * Show the form for editing the specified repository.
     *
     * Displays the same form as create, but prefilled with the
     * current repository data for editing.
     *
     * @param Repository $repository The repository to edit (auto-injected)
     * @return View The edit form view with repository data
     */
    public function edit(Repository $repository): View
    {
        return view("repositories.edit", [
            "repository" => $repository,
        ]);
    }

    /**
     * Update the specified repository in the database.
     *
     * This method handles the PUT/PATCH request from the edit form.
     * It validates the input and updates the existing repository record.
     *
     * @param Request $request The incoming HTTP request with form data
     * @param Repository $repository The repository to update (auto-injected)
     * @return RedirectResponse Redirects to index with success message
     */
    public function update(
        Request $request,
        Repository $repository,
    ): RedirectResponse {
        // Validate the incoming request data
        // Same validation rules as store()
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "url" => "required|url|max:255",
            "description" => "nullable|string|max:255",
            "guide" => "required|string",
        ]);

        // Update the repository with validated data
        // The update() method only updates fields that are in $fillable
        $repository->update($validated);

        // Redirect to index page with success message
        return redirect()
            ->route("home")
            ->with("success", "Entity updated successfully");
    }

    /**
     * Remove the specified repository from the database.
     *
     * This method handles the DELETE request to remove a repository.
     * The delete action is triggered from a form on the index page.
     *
     * @param Repository $repository The repository to delete (auto-injected)
     * @return RedirectResponse Redirects to index with success message
     */
    public function destroy(Repository $repository): RedirectResponse
    {
        // Delete the repository from the database
        // This is a "hard delete" - the record is permanently removed
        // For "soft deletes" (keeping records but marking as deleted),
        // you would use the SoftDeletes trait in the model
        $repository->delete();

        // Redirect to index page with success message
        return redirect()
            ->route("home")
            ->with("success", "Entity deleted successfully");
    }
}
