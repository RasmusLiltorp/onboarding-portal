<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $repositories = Auth::user()->repositories;
        return view("favorites.index", ["repositories" => $repositories]);
    }

    public function store(Repository $repository): RedirectResponse
    {
        Auth::user()->repositories()->attach($repository->id);
        return redirect()->back();
    }
}
