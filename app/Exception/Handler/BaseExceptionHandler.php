<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use App\Helper\ResponseFormatTrait;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class BaseExceptionHandler extends ExceptionHandler
{
    use ResponseFormatTrait;
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        return $this->errorResponse($response, 500, 'server error');
    }

    protected function errorResponse(ResponseInterface $response, int $statusCode, string $errorMessage)
    {
        return $response
            ->withStatus($statusCode)
            ->withHeader('Content-type', 'application/json')
            ->withBody(new SwooleStream(
                $this->errorFormat($statusCode, $errorMessage)));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
