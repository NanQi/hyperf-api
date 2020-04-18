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


use App\Helper\ResponseFormatTrait;
use App\Request\FooRequest;
use App\Service\JwtService;
use Co\Mysql\Exception;
use Hyperf\DbConnection\Annotation\Transactional;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use App\Middleware\AuthMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\DbConnection\Db;

/**
 * @AutoController()
 */
class UserController extends BaseController
{

    use ResponseFormatTrait;
    /**
     * @Inject()
     * @var JwtService
     */
    private $jwtService;

    # 模拟登录,获取token
    public function login()
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        if ($username && $password) {
            $userData = [
                'user_id' => 1,
                'username' => 'xx',
            ];
            $user_id = '123456';
            $token = $this->jwtService->getToken($user_id);
            var_dump('jwt:'.$token);
            return $this->response->json(['data' => ['token' => $token.'']]);
        }
        return $this->retError(425,'登录失败');
    }

    # 注册
    public function register()
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');

        var_dump($username .'--'.$password);
        $user_id = '123456';
        $token = $this->jwtService->getToken($user_id);
        return $this->response->json(['data' => ['token' => $token.'']]);
    }

    /**
     * @Middleware(AuthMiddleware::class)
     */
    # http头部必须携带token才能访问的路由
    public function info()
    {
        $uid = $this->request->getAttribute('uid');
        var_dump('获取用户ID：'.$uid);
        //$user = $this->request->user;
        //$request = Context::get(ServerRequestInterface::class);
        //var_dump($request->getAttributes()['user']->username);
    }
}
