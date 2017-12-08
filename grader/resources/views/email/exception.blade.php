<!doctype html><html><body>

<p>There was an exception thrown in {{ config('app.name') }}.</p>
<p>{{ get_class($exception) }}</p>
<p>{{ $exception->getMessage() }}</p>

<h2>User</h2>
<pre>{{ $user }}</pre>

<h2>Request</h2>
<pre>{{ $request }}</pre>

@unless(empty($validations))
    <h2>Validation</h2>
    <pre>{{ $validations }}</pre>
@endunless

<h2>Exception</h2>
<pre>{{ $exception }}</pre>

</body></html>
