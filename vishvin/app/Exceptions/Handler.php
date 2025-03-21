<?php

namespace App\Exceptions;

use Throwable;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


use ParseError;
use Illuminate\Database\QueryException;
// use Symfony\Component\Debug\Exception\FatalErrorException;
// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



class Handler extends ExceptionHandler
{
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

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ParseError) {
            return response()->view('errors.syntax', [], 500);
        }

        if ($exception instanceof QueryException) {
            return response()->view('errors.database', [], 500);
        }

        // if ($exception instanceof NotFoundHttpException) {
        //     return response()->view('errors.404', [], 404);
        // }

        return parent::render($request, $exception);
    }

}
