<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ValidationExceptionHandler extends BaseExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
//        $this->stopPropagation();
        /** @var \Hyperf\Validation\ValidationException $throwable */
        $body = $throwable->validator->errors()->first();

        return $this->errorResponse($response, $throwable->status, $this->errorFormat($throwable->status, $body));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}
