<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;

class UserUnverifiedPhoneException extends ApplicationHttpException
{
    public function __construct(
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(
            ApplicationHttpException::APP_ERROR_CODE['UNVERIFIED_PHONE'],
            $message,
            $previous,
            $headers,
            $code
        );
    }
}
