<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\CircuitBreaker\Annotation\CircuitBreaker;
use Hyperf\Config\Annotation\Value;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Guzzle\CoroutineHandler;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Redis\RedisFactory;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;

class CircuitService extends BaseService
{
    /**
     * @var ClientFactory
     */
    private $clientFactory;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    /**
     * @CircuitBreaker(timeout=1, duration=5, failCounter=1, successCounter=1, fallback="App\Service\CircuitService::searchFallback")
     * @return string
     */
    public function search()
    {
        $client = new Client([
            'base_uri' => 'http://127.0.0.1:9502',
            'handler' => HandlerStack::create(new CoroutineHandler()),
            'timeout' => 5,
            'swoole' => [
                'timeout' => 10,
                'socket_buffer_size' => 1024 * 1024 * 2,
            ],
        ]);

        $ran = rand(1, 5);

        $res = $client->get("?sleep=" . $ran);
        return $res->getBody()->getContents();
    }

    public function searchFallback()
    {
        return 'fallback3';
    }
}
