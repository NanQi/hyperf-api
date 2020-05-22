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

use App\Model\UserModel;
use App\Request\FooRequest;
use App\Service\JwtService;
use Hyperf\Contract\ConfigInterface;
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
use Psr\Http\Message\ResponseInterface;

/**
 * @Controller(prefix="v1/test")
 * Class TestController
 * @package App\Controller
 */
class TestController extends BaseController
{
    /**
     * @GetMapping(path="reload")
     * @return string
     */
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
     */
    public function log(JwtService $jwtService)
    {
        $ret = $jwtService->checkToken();
        return $ret;
    }

    /**
     * @GetMapping(path="token")
     */
    public function token(JwtService $jwtService)
    {
        $token = $jwtService->getToken(25);
        return $token;
    }

    /**
     * @GetMapping(path="check")
     */
    public function checkToken(JwtService $jwtService)
    {
        $flg = $jwtService->checkToken();
        return ['flg' => $flg];
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
     * @return ResponseInterface
     */
    public function foo(FooRequest $request)
    {
        return $this->response->json(['name' => 'foo5']);
    }

    /**
     * @GetMapping(path="config")
     */
    public function config()
    {
        $config = $this->container->get(ConfigInterface::class);
        var_dump(array_keys($config->get('redis')));
    }
}
