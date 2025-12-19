{{--
    Repository Index Page

    Displays a list of all repositories with actions to view, edit, and delete.
--}}
@extends('layouts.app')

@section('title', 'Repositories - Onboardo')

@section('content')
    <h1>Repositories</h1>

    {{-- List of repositories --}}
    <ul class="repo-list">
        @foreach ($repositories as $repository)
            <li class="repo-list__item">
                {{-- Repository info --}}
                <div class="repo-list__info">
                    <span class="repo-list__name">{{ $repository['name'] }}</span>
                    <span class="repo-list__description">{{ $repository['description'] }}</span>
                </div>

                {{-- Action links --}}
                <div class="repo-list__actions">
                    <a href="{{ route('repositories.show', $repository['id']) }}">View</a>
                    <a href="{{ route('repositories.edit', $repository['id']) }}">Edit</a>
                    <button type="button" data-delete-id="{{ $repository['id'] }}" data-delete-name="{{ $repository['name'] }}">Delete</button>
                </div>
            </li>
        @endforeach
    </ul>
@endsection
