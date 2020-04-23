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
use App\Exception\Handler\AppExceptionHandler;
use App\Exception\Handler\BusinessExceptionHandler;
use App\Exception\Handler\ValidationExceptionHandler;
return [
    'handler' => [
        'http' => [
            ValidationExceptionHandler::class,
            BusinessExceptionHandler::class,
            AppExceptionHandler::class,
        ],
    ],
];
