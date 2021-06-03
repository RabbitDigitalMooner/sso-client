<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;

class SsoUnknownResponseException extends SsoServerErrorException
{
    public function __construct(
        $code,
        $message = null,
        Exception $previous = null,
        array $headers = []
    ) {
        parent::__construct(
            SsoServerErrorException::SSO_ERROR_CODE['SSO_SERVICE_UNKNOWN_ERROR'],
            $message,
            $previous,
            $headers,
            $code
        );
    }
}
