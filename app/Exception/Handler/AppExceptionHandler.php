<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends BaseExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->logger->error(sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile()));
        $this->logger->error($throwable->getTraceAsString());

        return $this->errorResponse($response, 500, 'server error');
    }
}
