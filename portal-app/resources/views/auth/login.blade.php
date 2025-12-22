{{--
    Login Page

    Allows existing users to authenticate.
    This page is only accessible to guests (non-authenticated users)
    via the 'guest' middleware on the route.

    After successful login, the user is redirected to the home page.
    If credentials are incorrect, an error message is displayed.
--}}
@extends('layouts.app')

@section('title', 'Log In - Onboardo')

@section('content')
    <h1>Log In</h1>

    {{--
        Login form
        Method: POST - authenticates the user
        Action: login.store route
    --}}
    <form action="{{ route('login.store') }}" method="POST" class="form">
        {{-- CSRF token for security --}}
        @csrf

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
            {{--
                Display error message if authentication fails.
                The controller returns withErrors(['email' => 'message'])
                which populates the $errors bag.
            --}}
            @error('email')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password field --}}
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
