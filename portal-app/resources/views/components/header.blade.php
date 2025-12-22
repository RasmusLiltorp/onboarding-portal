{{--
    Header Component

    Site-wide header with navigation links.
    This is an anonymous Blade component - no PHP class needed since it's pure markup.

    We use the `route()` helper to generate URLs from named routes.
    This is best practice - if route URLs change, views don't need updating.

    Usage: <x-header /> in any view or layout
    Included in: resources/views/layouts/app.blade.php
--}}
<header class="header">
    <div class="header__container">

        <a href="{{ route('home') }}" class="header__brand">Onboardo</a>

        <nav class="header__nav">
            <a href="{{ route('repositories.create') }}">New Repository</a>
            @guest
                <a href="{{ route('registration.create') }}">Sign Up</a>
                <a href="{{ route('login') }}">Log In</a>
            @endguest
            @auth
                <a href="{{ route('registered.index') }}">My Favorites</a>
                <span>{{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('login.destroy') }}" dusk="logout">
                    @csrf
                    <button type="submit">Log Out</button>
                </form>
            @endauth
        </nav>
    </div>
</header>
