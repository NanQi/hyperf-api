<?php
/**
 * author: NanQi
 * datetime: 2020/5/23 19:17
 */

namespace App\Client;


use GuzzleHttp\HandlerStack;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\Guzzle\CoroutineHandler;

class BaseClient
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    public function __construct(ClientFactory $clientFactory)
    {
        // $options 等同于 GuzzleHttp\Client 构造函数的 $config 参数
        $options = [
            'handler' => HandlerStack::create(new CoroutineHandler()),
            'timeout' => 5,
            'swoole' => [
                'timeout' => 10,
                'socket_buffer_size' => 1024 * 1024 * 2,
            ],
        ];
        // $client 为协程化的 GuzzleHttp\Client 对象
        $client = $clientFactory->create($options);
        $this->client = $client;
    }
}