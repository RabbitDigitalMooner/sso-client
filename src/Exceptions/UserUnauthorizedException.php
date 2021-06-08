<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;

class UserUnauthorizedException extends ApplicationHttpException
{
    public function __construct(
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(
            ApplicationHttpException::APP_ERROR_CODE['UNAUTHORIZED'],
            $message,
            $previous,
            $headers,
            $code
        );
    }
}
