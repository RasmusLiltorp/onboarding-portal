{{--
    Repository Index Page

    Displays a list of all repositories with actions to view, edit, and delete.

    Dusk attributes for testing:
    - dusk="element"    : Each repository item in the list
    - dusk="to-create"  : Link to the create page
    - dusk="to-show"    : Link to view repository details
    - dusk="to-edit"    : Link to edit repository
    - dusk="to-delete"  : Button/form to delete repository
    - dusk="success-msg": Flash message container for success messages
--}}
@extends('layouts.app')

@section('title', 'Repositories - Onboardo')

@section('content')
    <h1>Repositories</h1>

    {{-- Success flash message - displayed after create/update/delete operations --}}
    @if (session('success'))
        <div class="alert alert--success" dusk="success-msg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Link to create a new repository --}}
    <a href="{{ route('repositories.create') }}" class="btn btn--primary" dusk="to-create">Add Repository</a>

    {{-- List of repositories --}}
    <ul class="repo-list">
        @foreach ($repositories as $repository)
            {{-- Each repository item has dusk="element" for testing --}}
            <li class="repo-list__item" dusk="element">
                {{-- Repository info --}}
                <div class="repo-list__info">
                    <span class="repo-list__name">{{ $repository->name }}</span>
                    <span class="repo-list__description">{{ $repository->description }}</span>
                </div>

                {{-- Action links/buttons --}}
                <div class="repo-list__actions">
                    {{-- View link with dusk="to-show" --}}
                    <a href="{{ route('repositories.show', $repository) }}" dusk="to-show">View</a>

                    {{-- Edit link with dusk="to-edit" --}}
                    <a href="{{ route('repositories.edit', $repository) }}" dusk="to-edit">Edit</a>

                    {{--
                        Delete form with dusk="to-delete"
                        Using a form instead of a link because DELETE is not a standard link method.
                        The @method('DELETE') directive adds a hidden _method field that Laravel
                        uses to determine the actual HTTP method (method spoofing).
                    --}}
                    <form action="{{ route('repositories.destroy', $repository) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" dusk="to-delete">Delete</button>
                    </form>
                </div>
            </li>
        @endforeach
    </ul>
@endsection
