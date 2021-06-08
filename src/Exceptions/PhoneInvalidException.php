<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;

class PhoneInvalidException extends ApplicationHttpException
{
    public function __construct(
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(
            ApplicationHttpException::APP_ERROR_CODE['PHONE_INVALID'],
            $message,
            $previous,
            $headers,
            $code
        );
    }
}
