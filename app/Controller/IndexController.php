<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Client\UserClient;
use Hyperf\CircuitBreaker\Annotation\CircuitBreaker;

class IndexController extends AbstractController
{
    /**
     * @CircuitBreaker(timeout=0.5, failCounter=3, successCounter=2, fallback="App\Client\UserClient::testFallback")
     */
    public function index(UserClient $userClient)
    {
        $url = $this->request->input('url', 'http://hf-api.k8s.ynyn.shop?name=v2');

        $res = $userClient->test($url);

        return $res;
    }
}
