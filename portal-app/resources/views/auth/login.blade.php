@extends('layouts.app')

@section('title', 'Log In - Onboardo')

@section('content')
    <h1>Log In</h1>

    <form action="{{ route('login.store') }}" method="POST" class="form">
        @csrf

        <div class="form__group">
            <label for="email" class="form__label">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                class="form__input"
                required
            >
            @error('email')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>

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
