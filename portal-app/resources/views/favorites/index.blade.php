@extends('layouts.app')

@section('title', 'My Favorites - Onboardo')

@section('content')
    <h1>My Favorites</h1>

    @if ($repositories->isEmpty())
        <p>You haven't added any favorites yet.</p>
    @else
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
