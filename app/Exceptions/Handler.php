<?php

namespace App\Exceptions;

use App\Enums\HttpStatusMessages;
use Throwable;
use App\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected $handlers = [
        ValidationException::class => 'handleValidation',
        NotFoundHttpException::class => 'handleNotFoundHttp',
        ModelNotFoundException::class => 'handleModelNotFound',
        AuthenticationException::class => 'handleAuthentication',
        AuthorizationException::class => 'handleAuthorization'
    ];

    private function handleValidation(ValidationException $exception) {
        foreach ($exception->errors() as $key => $value)
            foreach ($value as $message) {
                $errors[] = $this->exceptionInfo(Response::HTTP_UNPROCESSABLE_ENTITY, $message, $key);
            }

            return $errors;
    }

    private function handleNotFoundHttp(NotFoundHttpException $exception) {
        Log::error($exception->getMessage());
        $errors[] = $this->exceptionInfo(Response::HTTP_NOT_FOUND, HttpStatusMessages::NOT_FOUND->value);
        return $errors;
    }

    private function handleModelNotFound(ModelNotFoundException $exception) {
        Log::error($exception->getMessage());
        $errors[] = $this->exceptionInfo(Response::HTTP_NOT_FOUND, HttpStatusMessages::NOT_FOUND->value, $exception->getModel());
        return $errors;
    }

    private function handleAuthentication(AuthenticationException $exception) {
        Log::error($exception->getMessage());
        $errors[] = $this->exceptionInfo(Response::HTTP_UNAUTHORIZED, HttpStatusMessages::UNAUTHENTICATED->value);
        return $errors;
    }

    private function handleAuthorization(AuthorizationException $exception) {
        Log::error($exception->getMessage());
        $errors[] = $this->exceptionInfo(Response::HTTP_UNAUTHORIZED, HttpStatusMessages::UNAUTHORIZED->value);
        return $errors;
    }

    private function getStatusCode($method) {
        return match ($method) {
            $this->handlers[ValidationException::class] => Response::HTTP_UNPROCESSABLE_ENTITY,
            $this->handlers[NotFoundHttpException::class], $this->handlers[ModelNotFoundException::class] => Response::HTTP_NOT_FOUND,
            $this->handlers[AuthenticationException::class], $this->handlers[AuthorizationException::class] => Response::HTTP_UNAUTHORIZED
        };
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception) {
        $className = get_class($exception);

        if (array_key_exists($className, $this->handlers)) {
            $method = $this->handlers[$className];
            return $this->error($this->$method($exception), $this->getStatusCode($method));
        }

        $index = strrpos($className, '\\');

        return $this->error([
            [
                'type' => substr($className, $index + 1),
                'status' => 0,
                'message' => $exception->getMessage(),
                //'source' => 'Line: ' . $exception->getLine() . ': ' . $exception->getFile()
            ]
        ]);
    }
}
