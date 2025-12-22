{{--
    Favorites Index Page

    Displays the authenticated user's favorite repositories.
    This page is only accessible to authenticated users
    via the 'auth' middleware on the route.

    The $repositories variable contains all repositories the user
    has added to their favorites (via the many-to-many relationship).
--}}
@extends('layouts.app')

@section('title', 'My Favorites - Onboardo')

@section('content')
    <h1>My Favorites</h1>

    {{-- Show message if user has no favorites --}}
    @if ($repositories->isEmpty())
        <p>You haven't added any favorites yet.</p>
    @else
        {{-- List all favorited repositories --}}
        <ul class="repo-list">
            @foreach ($repositories as $repository)
                <li class="repo-list__item">
                    <a href="{{ route('repositories.show', $repository) }}">{{ $repository->name }}</a>
                </li>
            @endforeach
        </ul>
    @endif

    <a href="{{ route('home') }}" class="btn btn--secondary">Back to list</a>
@endsection
