@extends('layouts.app')

@section('title', 'Sign Up - Onboardo')

@section('content')
    <h1>Sign Up</h1>

    <form action="{{ route('registration.store') }}" method="POST" class="form">
        @csrf

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
