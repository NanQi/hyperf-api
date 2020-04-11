<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ValidationExceptionHandler extends BaseExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();
        /** @var \Hyperf\Validation\ValidationException $throwable */
        $body = json_encode([
            'code' => $throwable->status,
            'message' => $throwable->validator->errors()->first()
        ], JSON_UNESCAPED_UNICODE);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($throwable->status)->withBody(new SwooleStream($body));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ValidationException;
    }
}
