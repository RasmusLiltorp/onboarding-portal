{{--
    Repository Show Page

    Displays a single repository with all its details.
--}}
@extends('layouts.app')

@section('title', $repository['name'] . ' - Onboardo')

@section('content')
    {{-- Repository header with name and actions --}}
    <div class="repo-header">
        <h1>{{ $repository['name'] }}</h1>
        <a href="{{ route('repositories.edit', $repository['id']) }}" class="btn btn--secondary">Edit</a>
    </div>

    {{-- Repository details --}}
    <div class="repo-details">
        {{-- URL --}}
        <div class="repo-details__item">
            <span class="repo-details__label">URL</span>
            <a href="{{ $repository['url'] ?? '#' }}" class="repo-details__value" target="_blank">
                {{ $repository['url'] ?? 'No URL provided' }}
            </a>
        </div>

        {{-- Description --}}
        <div class="repo-details__item">
            <span class="repo-details__label">Description</span>
            <p class="repo-details__value">{{ $repository['description'] ?? 'No description' }}</p>
        </div>

        {{-- Onboarding Guide --}}
        <div class="repo-details__item">
            <span class="repo-details__label">Onboarding Guide</span>
            <div class="repo-details__guide">
                {{ $repository['guide'] ?? 'No guide yet' }}
            </div>
        </div>
    </div>

    {{-- Back link --}}
    <a href="{{ route('repositories.index') }}" class="btn btn--secondary">Back to list</a>
@endsection
