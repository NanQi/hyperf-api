<?php

declare(strict_types=1);


namespace App\Exception;

use Hyperf\Server\Exception\ServerException;

/**
 * Class BusinessException
 * @var int $statusCode
 * @var string $errorMessage
 * @var int $errorCode
 * @package App\Exception
 */
class BusinessException extends ServerException
{
    public $statusCode;
    public $errorMessage;
    public $errorCode;

    public function __construct(int $statusCode, string $errorMessage, int $errorCode = 0)
    {
        parent::__construct($errorMessage);

        $this->statusCode = $statusCode;
        $this->errorMessage = $errorMessage;
        $this->errorCode = $errorCode;
    }
}
