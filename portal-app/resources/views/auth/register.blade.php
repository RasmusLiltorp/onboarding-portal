{{--
    Registration Page

    Allows new users to create an account.
    This page is only accessible to guests (non-authenticated users)
    via the 'guest' middleware on the route.

    After successful registration, the user is automatically logged in
    and redirected to the home page.
--}}
@extends('layouts.app')

@section('title', 'Sign Up - Onboardo')

@section('content')
    <h1>Sign Up</h1>

    {{--
        Registration form
        Method: POST - creates a new user resource
        Action: registration.store route
    --}}
    <form action="{{ route('registration.store') }}" method="POST" class="form">
        {{-- CSRF token for security --}}
        @csrf

        {{-- Name field --}}
        <div class="form__group">
            <label for="name" class="form__label">Name</label>
            <input
                type="text"
                id="name"
                name="name"
                class="form__input"
                required
            >
        </div>

        {{-- Email field --}}
        <div class="form__group">
            <label for="email" class="form__label">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                class="form__input"
                required
            >
        </div>

        {{-- Password field - will be hashed by Laravel before storing --}}
        <div class="form__group">
            <label for="password" class="form__label">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                class="form__input"
                required
            >
        </div>

        <input type="submit" class="btn btn--primary" value="Submit">
    </form>
@endsection
