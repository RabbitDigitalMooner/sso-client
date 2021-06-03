<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;

class CountryNotFoundException extends LocationException
{
    public function __construct(
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(
            LocationException::LOCATION_ERROR_CODE['COUNTRY_NOT_FOUND'],
            $message,
            $previous,
            $headers,
            $code
        );
    }
}
