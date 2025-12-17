{{--
    Welcome/Home Page

    The landing page users see when visiting the site.
    Extends the main layout and injects content into the 'content' section.
--}}
@extends('layouts.app')

@section('title', 'Home - Repository Onboarder')

@section('content')
    <h1>Welcome to Repository Onboarder</h1>
    <p>Create and manage onboarding guides for your repositories.</p>
@endsection
