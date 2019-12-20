<?php

declare(strict_types=1);

namespace App\Helper;

use App\Exception\BusinessException;

trait ResponseFormatTrait {

    public function errorFormat(int $statusCode, string $errorMessage, int $errorCode = 0) {
        if ($errorCode === 0) {
            $errorCode = $statusCode;
        }

        $ret = [
            'code' => $errorCode,
            'msg' => $errorMessage
        ];

        return json_encode($ret);
    }

    /**
     * 返回错误
     * @param int $statusCode
     * @param string $errorMessage
     * @param int $errorCode
     */
    public function retError(int $statusCode, string $errorMessage, int $errorCode = 0)
    {
        throw new BusinessException($statusCode, $errorMessage, $errorCode);
    }
}