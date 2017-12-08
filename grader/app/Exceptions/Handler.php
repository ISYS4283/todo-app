<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Request;
use Auth;
use Mail;
use App\User;
use App;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (in_array(get_class($exception), $this->dontReport)) {
            return;
        }

        if (App::Environment() === 'testing') {
            return;
        }

        $user = Auth::user();
        if ($user instanceof User) {
            $user = json_encode($user->toArray(), JSON_PRETTY_PRINT);
        }

        $data = [
            'exception' => $exception,
            'user'      => Request::ip() . PHP_EOL . $user,
            'request'   => Request::fullUrl() . PHP_EOL
                        . print_r(Request::all(), true),
        ];

        if ($exception instanceof ValidationException) {
            $data['validations'] = $this->getValidationErrors($exception);
        }

        Mail::send('email.exception', $data, function ($message) {
            $message->to(config('mail.err'))
                    ->subject('THROWN '.config('app.name'));
        });

        parent::report($exception);
    }

    protected function getValidationErrors(ValidationException $exception) : string
    {
        $failed = $exception->validator->failed();
        $messages = $exception->validator->errors()->messages();
        return print_r(array_merge_recursive($failed, $messages), true);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }
}
