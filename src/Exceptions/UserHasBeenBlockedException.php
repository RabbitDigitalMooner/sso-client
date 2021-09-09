<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;

class UserHasBeenBlockedException extends ApplicationHttpException
{
    public function __construct(
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(
            ApplicationHttpException::APP_ERROR_CODE['USER_HAS_BEEN_BLOCKED'],
            $message,
            $previous,
            $headers,
            $code
        );
    }
}
