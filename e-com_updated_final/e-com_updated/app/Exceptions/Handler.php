<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {
        $this->renderable(function (Throwable $e, $request) {
            // For AJAX or JSON requests, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Oops! Something went wrong. Please try again later.'
                ], 500);
            }
            // For web requests, show a friendly error page
            return response()->view('errors.custom', [
                'message' => 'Oops! Something went wrong. Please try again later.'
            ], 500);
        });
    }
}
