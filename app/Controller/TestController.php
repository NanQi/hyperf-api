<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Request\FooRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\Redis\RedisFactory;
use Hyperf\Snowflake\IdGeneratorInterface;
use App\Middleware\AuthMiddleware;
use PDepend\Util\Log;

/**
 * @Controller(prefix="v1/test")
 * @Middleware(AuthMiddleware::class)
 * Class TestController
 * @package App\Controller
 */
class TestController extends AbstractController
{
    public function reload()
    {
        return '5';
    }
    /**
     * @Inject
     * @var IdGeneratorInterface
     */
    private $idGenerator;


    /**
     * @GetMapping(path="snowflake")
     * @return int
     */
    public function snowflake()
    {
         $id = $this->idGenerator->generate();
         return $id;
    }

    /**
     * @Inject
     * @var RedisFactory
     */
    private $redisFactory;

    /**
     * @GetMapping(path="redis")
     * @return bool|string
     */
    public function redis()
    {
        $redis = $this->redisFactory->get('default');
        $redis->set('name', 'nanqi');

        $name = $redis->get('name');
        return $name;
    }

    /**
     * @GetMapping(path="log")
     * @return string
     */
    public function log()
    {
        return 'log';
    }

    /**
     * @PostMapping(path="post")
     */
    public function testPost()
    {
        return 'post';
    }

    /**
     * @PostMapping(path="foo")
     * @param FooRequest $request
     */
    public function foo(FooRequest $request)
    {
        $this->retError(411, 'test');

        return $this->response->json(['name' => 'foo5']);
    }
}
