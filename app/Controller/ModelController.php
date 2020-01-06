<?php

declare(strict_types=1);


namespace App\Controller;

use App\Model\UserModel;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * @Controller(prefix="v1/model")
 * @package App\Controller
 */
class ModelController extends BaseController
{
    /**
     * @PostMapping(path="add")
     */
    public function add()
    {
        $user = new UserModel();
        $user->save();
    }

    /**
     * @GetMapping(path="list")
     */
    public function list()
    {
        $list = UserModel::all();
        return $list;
    }
}
