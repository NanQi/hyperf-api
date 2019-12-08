<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

/**
 * @AutoController
 * Class Test2Controller
 * @package App\Controller
 */
class Test2Controller
{
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return 'nanqi';
    }
}
