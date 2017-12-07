@extends('layout')

@section('content')
    <h1>Welcome</h1>

    <p class="lead">
        Follow
            <a href="https://github.com/ISYS4283/todo-app"
               target='_blank' rel="noopener noreferrer">
               this tutorial
            </a>
    </p>

    <p>
        Then use the <kbd>Get Token</kbd> button on the todo app's login form.
    </p>

    <p>
        Make sure your server is up and running while submitting.
        This will initiate a connection and attempt a series of transactions
        in order to verify functionality and correct permissions applied.
    </p>

    <form method="post">
        {{ csrf_field() }}

        <div class="form-group {{ $errors->has('ip-address') ? 'has-error' : '' }}">
            <label for="ip-address" class="control-label">Server IP Address</label>
            <input class="form-control"
                   type="text"
                   name="ip-address"
                   placeholder="for example: 10.9.0.198"
                   required
                   autofocus
                   value="{{ old('ip-address') }}">
            @if ($errors->has('ip-address'))
                @foreach ($errors->get('ip-address') as $msg)
                    <span class="label label-danger">{{ $msg }}</span>
                @endforeach
            @endif
        </div>

        <div class="form-group {{ $errors->has('user-token') ? 'has-error' : '' }}">
            <label for="user-token" class="control-label">User Token</label>
            <input class="form-control"
                   type="text"
                   name="user-token"
                   placeholder="for example: eyJ1c2VybmFtZSI6ImplZmYiLCJwYXNzd29yZCI6IklTWVM0MjgzIGlzIHRoZSBiZXN0ISIsImRhdGFiYXNlIjoidG9kb2FwcCIsImhvc3RuYW1lIjoibG9jYWxob3N0In0="
                   required
                   value="{{ old('user-token') }}">
            @if ($errors->has('user-token'))
                @foreach ($errors->get('user-token') as $msg)
                    <span class="label label-danger">{{ $msg }}</span>
                @endforeach
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
