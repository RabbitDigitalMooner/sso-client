<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;

class CodeVerificationException extends ApplicationHttpException
{
    public function __construct(
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(
            ApplicationHttpException::APP_ERROR_CODE['CODE_NOT_FOUND'],
            $message,
            $previous,
            $headers,
            $code
        );
    }
}
