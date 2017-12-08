@extends('razorbacks::layout')

@section('navbar')
    <li><a href="https://github.com/ISYS4283/todo-app"
       target='_blank' rel="noopener noreferrer">
       Tutorial
    </a></li>
@endsection

@section('navbar-right')
    @auth
        <li><a href="/shibboleth-logout">Logout {{ Auth::user()->name }}</a></li>
    @else
        <li><a href="/shibboleth-login">Login</a></li>
    @endauth
@endsection
