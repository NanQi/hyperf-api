<?php
declare(strict_types=1);

namespace App\Exception\Handler;

use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Phper666\JwtAuth\Exception\TokenValidException;
use Throwable;
use App\Helpers\Helper;
use App\Helpers\Code;

class JwtExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 判断被捕获到的异常是希望被捕获的异常
        if ($throwable instanceof TokenValidException) {
            // 格式化输出
            $data = json_encode([
                'code' => $throwable->getCode(),
                'message' => $throwable->getMessage(),
            ], JSON_UNESCAPED_UNICODE);

            // 阻止异常冒泡
            $this->stopPropagation();
            return $response->withStatus($throwable->getCode())
                ->withAddedHeader('content-type', 'application/json')
                ->withBody(new SwooleStream($data));
        }

        // 交给下一个异常处理器
        return $response;
        // 或者不做处理直接屏蔽异常
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof TokenValidException;
    }

}