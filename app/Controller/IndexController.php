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

class IndexController extends AbstractController
{
    public function index(UserClient $userClient)
    {
        $url = $this->request->input('url', 'http://hf-api.k8s.ynyn.shop/');

        $res = $userClient->test($url);

        return $res;
    }
}
