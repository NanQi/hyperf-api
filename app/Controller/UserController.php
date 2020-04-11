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
use Co\Mysql\Exception;
use Hyperf\DbConnection\Annotation\Transactional;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use Phper666\JwtAuth\Jwt;
use App\Middleware\JwtAuthMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\DbConnection\Db;
use App\Model\UserModel;
/**
 * @AutoController()
 */
class UserController extends BaseController
{
    /**
     * @Inject()
     * @var Jwt
     */
    protected $jwt;
    /**
     * @OA\Get(
     *     path="/page/",
     *     tags={"页面"},
     *     summary="首页信息",
     *     description="首页信息",
     *     security={{"jwt":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *            @OA\Property(property="money",type="string",description="余额"),
     *            @OA\Property(property="jifen",type="string",description="积分"),
     *            ),
     *         )
     *     ),
     * )
     */
    public function index()
    {
        #redis 使用
        $container = ApplicationContext::getContainer();
        $redis = $container->get(\Redis::class);
        $result = $redis->keys('*');
        var_dump($result);
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();

        return [
            'method' => $method,
            'message' => "Hello {$user}.",
        ];
    }

    # 模拟登录,获取token
    public function login(Jwt $jwt)
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');
        if ($username && $password) {
            $userData = [
                'user_id' => 1,
                'username' => 'test',
                'phone' => '18049552556'
            ];
            $token = (string)$jwt->getToken($userData);
            return $this->response->json(['data' => ['token' => $token]]);
        }

        return $this->response->json(['code' => 0, 'msg' => '登录失败', 'data' => []]);
    }


    # 注册
    public function register()
    {
        $username = $this->request->input('username');
        $password = $this->request->input('password');

        #事务示例1
//        Db::transaction(function () {
//            Db::table('user')->where('user_id',1)->update(['sex' => 2]);
//            Db::table('user')->where('user_id',1)->update(['password1' => 123456]);
//        });

        #事务示例 2
        //@Transactional()
        #事务示例 3
        Db::beginTransaction();
        try{
            Db::table('user')->where('user_id',1)->update(['sex' => 2]);
            Db::table('user')->where('user_id',1)->update(['password' => 123456]);

            Db::commit();
        } catch(\Throwable $ex){
            var_dump('触发异常');
            Db::rollBack();
        }

        //return $this->response->json(['code' => 0, 'msg' => '登录失败', 'data' => []]);
    }

    /**
     * @Middleware(JwtAuthMiddleware::class)
     */
    # http头部必须携带token才能访问的路由
    public function info()
    {
        //获取用户信息
        $user = $this->request->getAttribute('user');
        var_dump($user);
        return $this->response->json(['data' => $user]);
    }

    /**
     * @Middleware(JwtAuthMiddleware::class)
     */
    # 刷新token，http头部必须携带token才能访问的路由
    public function refreshToken()
    {
        $token = $this->jwt->refreshToken();
        $data = [
            'data' => [
                'token' => (string)$token,
                'exp' => $this->jwt->getTTL(),
            ]
        ];
        return $this->response->json($data);
    }

    /**
     * @Middleware(JwtAuthMiddleware::class)
     */
    # 注销token，http头部必须携带token才能访问的路由
    public function logout()
    {
        $this->jwt->logout();
        return $this->response->withStatus(200);
    }

}
