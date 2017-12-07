@extends('razorbacks::layout')

@section('navbar-right')
    @auth
        <li><a href="/shibboleth-logout">Logout {{ Auth::user()->name }}</a></li>
    @else
        <li><a href="/shibboleth-login">Login</a></li>
    @endauth
@endsection
