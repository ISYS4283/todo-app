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

    <form method="post">
        {{ csrf_field() }}

        <div class="form-group {{ $errors->has('ip-address') ? 'has-error' : '' }}">
            <label for="ip-address" class="control-label">IP Address</label>
            <input class="form-control"
                   type="text"
                   name="ip-address"
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
