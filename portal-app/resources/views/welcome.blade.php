{{--
    Landing Page

    The home page users see when visiting the site.
    Features a hero section with call-to-action buttons.
--}}

@extends('layouts.app')

@section('title', 'Home - Repository Onboarder')

@section('content')
<section class="hero">
      <h1 class="hero__title">Welcome to Onboardo</h1>
      <p class="hero__tagline">Create onboarding guides for your repositories in minutes.</p>

      {{-- Call-to-action buttons --}}
      <div class="hero__actions">
        <a href="{{ route('repositories.create') }}" class="btn btn--primary">Get Started</a>
        <a href="{{ route('repositories.index') }}" class="btn btn--secondary">Browse Repos</a>
      </div>
  </section>
@endsection
