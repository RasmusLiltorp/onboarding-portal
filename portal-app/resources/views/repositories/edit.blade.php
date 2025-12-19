{{--
    Repository Edit Page

    Form to edit an existing repository.
    Similar to create page but with prefilled values.
--}}
@extends('layouts.app')

@section('title', 'Edit ' . $repository['name'] . ' - Onboardo')

@section('content')
    <h1>Edit {{ $repository['name'] }}</h1>

    {{--
        Repository edit form
        Method: PUT - used for updating existing resources
        Note: HTML forms only support GET/POST, so we use @method('PUT') to spoof it
    --}}
    <form action="{{ route('repositories.update', $repository['id']) }}" method="POST" class="form">
        @csrf
        {{--
            Method spoofing - HTML forms don't support PUT/PATCH/DELETE
            This creates a hidden _method field that Laravel reads to determine the actual method
        --}}
        @method('PUT')

        {{-- Repository name field --}}
        <div class="form__group">
            <label for="name" class="form__label">Name</label>
            <input
                type="text"
                id="name"
                name="name"
                class="form__input"
                value="{{ $repository['name'] }}"
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
                value="{{ $repository['url'] ?? '' }}"
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
                value="{{ $repository['description'] ?? '' }}"
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
                required
            >{{ $repository['guide'] ?? '' }}</textarea>
        </div>

        {{-- Submit button --}}
        <button type="submit" class="btn btn--primary">Update Repository</button>
    </form>

    {{-- Back link --}}
    <a href="{{ route('repositories.show', $repository['id']) }}" class="btn btn--secondary" style="margin-top: 1rem; display: inline-block;">Cancel</a>
@endsection
