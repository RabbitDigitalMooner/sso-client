<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;

class BindingNotFoundException extends ApplicationHttpException
{
    public function __construct(
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(
            ApplicationHttpException::APP_ERROR_CODE['BINDING_NOT_FOUND'],
            $message,
            $previous,
            $headers,
            $code
        );
    }
}
