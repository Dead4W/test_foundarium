<?php

namespace App\Framework\Exceptions;

use App\Common\Exceptions\ResponseableException;
use App\Framework\Http\Controllers\Controller;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function __construct(
        Container $container,
        protected Controller $baseController,
    ) {
        parent::__construct($container);
    }

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        if (config('app.debug') === true) {
            return parent::render($request, $e);
        }

        if ($e instanceof ModelNotFoundException) {
            $message = __('errors.not_found');
            $errors = [
                __('errors.not_found')
            ];
            $code = 404;
        } elseif ($e instanceof ResponseableException) {
            $message = $e->getResponseMessage();
            $errors = $e->getResponseErrors();
            $code = $e->getResponseCode();
        } else {
            $message = __('auth.unexpected_error');
            $errors = [
                __('auth.unexpected_error')
            ];
            $code = 500;
        }

        return $this->baseController->failedResponse(
            message: $message,
            errors:  $errors,
            code:    $code,
        );
    }
}
