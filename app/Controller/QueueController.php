<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\QueueService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController
 */
class QueueController extends BaseController
{
    /**
     * @Inject
     * @var QueueService
     */
    protected $service;

    /**
     * 传统模式投递消息
     */
    public function index()
    {
        for($i=0;$i<100000;$i++){
            $this->service->push('-----'.$i);
        }


        return 'success';
    }
}
