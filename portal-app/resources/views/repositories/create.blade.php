{{--
    Repository Create Page

    Form to create a new repository with onboarding guide.
--}}
@extends('layouts.app')

@section('title', 'New Repository - Onboardo')

@section('content')
    <h1>New Repository</h1>

    {{--
        Repository creation form
        Method: POST - used for creating new resources
        Action: Will submit to repositories.store route
    --}}
    <form action="{{ route('repositories.store') }}" method="POST" class="form">
        {{--
            CSRF token - Cross-Site Request Forgery protection
            This generates a hidden input with a unique token that Laravel validates.
            It prevents attackers from submitting forms on behalf of logged-in users.
            Laravel automatically rejects POST/PUT/DELETE requests without a valid token.
        --}}
        @csrf

        {{-- Repository name field --}}
        <div class="form__group">
            <label for="name" class="form__label">Name</label>
            <input
                type="text"
                id="name"
                name="name"
                class="form__input"
                placeholder="my-awesome-repo"
                required
            >
        </div>

        {{-- Repository URL field --}}
        <div class="form__group">
            <label for="url" class="form__label">URL</label>
            <input
                type="url"
                id="url"
                name="url"
                class="form__input"
                placeholder="https://github.com/username/repo"
                required
            >
        </div>

        {{-- Repository description field --}}
        <div class="form__group">
            <label for="description" class="form__label">Description</label>
            <input
                type="text"
                id="description"
                name="description"
                class="form__input"
                placeholder="A short description of the repository"
            >
        </div>

        {{-- Onboarding guide field --}}
        <div class="form__group">
            <label for="guide" class="form__label">Onboarding Guide</label>
            <textarea
                id="guide"
                name="guide"
                class="form__textarea"
                rows="10"
                placeholder="Write your onboarding guide here..."
                required
            ></textarea>
        </div>

        {{-- Submit button --}}
        <button type="submit" class="btn btn--primary">Create Repository</button>
    </form>
@endsection
