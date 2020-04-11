<?php

declare(strict_types = 1);

namespace App\Middleware;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Phper666\JwtAuth\Jwt;
use Phper666\JwtAuth\Exception\TokenValidException;
use App\Model\UserModel;

class JwtAuthMiddleware implements MiddlewareInterface
{

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var Jwt
     */
    protected $jwt;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $token = $this->jwt->getTokenObj();
            if ($this->jwt->checkToken()) {
                $user['user_id'] = $token->getClaim('user_id');
                $user['username'] = $token->getClaim('username');
                $user['phone'] = $token->getClaim('phone');
                $request = $request->withAttribute('user',$user);
                Context::set(ServerRequestInterface::class, $request);
            }
        } catch (\Exception $e) {
            throw new TokenValidException('Token未验证通过', 401);
        }
        return $handler->handle($request);
    }

}
