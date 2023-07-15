<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/*
*/

class Handler extends ExceptionHandler
{
	protected $dontReport = [];

	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	public function report(Exception $exception)
	{
		parent::report($exception);
	}

	public function render($request, Exception $exception)
	{
		if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
			if (auth()->guest()) {
				return redirect()->route('landing.index');
			} else {
				notificacion('Dirección equivocada', 'Por favor verifique la dirección ', 'error');
				return redirect()->route('home');
			}
		}
    //
    if ($exception instanceof AuthorizationException) {
      $exception = new AccessDeniedHttpException('Esta acción no está autorizada', $exception);
    }

		if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
			if (auth()->check()) {
			}
			noti()->warning('Acceso restringido', '-- Usted no tiene acceso a esta area ');
			return redirect()->route('home');
		}

		return parent::render($request, $exception);
	}
}
