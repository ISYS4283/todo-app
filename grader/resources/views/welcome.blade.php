@extends('layout')

@section('content')
    <h1>Welcome</h1>

    <p class="lead">
        Follow the
            <a href="https://github.com/ISYS4283/todo-app"
               target='_blank' rel="noopener noreferrer">
               tutorial
           </a>
        and then create:
    </p>

    <ul>
        <li>A new regular user.</li>
        <li>A new administrative user, other than root.</li>
    </ul>

    <p>
        Then enter the IP address for your server,
        and use the <kbd>Get Token</kbd> button on the todo app's login form
        for the respective users.
    </p>

    <p>
        Make sure your server is up and running while submitting.
        This will initiate a connection and attempt a series of transactions
        in order to verify functionality and correct permissions applied.
    </p>

    {{ Form::open(['url' => '/']) }}
        {{ Form::bsText('IP Address') }}
        {{ Form::bsText('User Token') }}
        {{ Form::bsText('Admin Token') }}
        <button type="submit" class="btn btn-primary">Submit</button>
    {{ Form::close() }}
@endsection
