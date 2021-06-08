<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;

class LocationException extends ApplicationHttpException
{
    public const LOCATION_ERROR_CODE = [
        'COUNTRY_NOT_FOUND' => 'country_not_found',
    ];

    protected static array $locationErrorHttpStatusCode = [
        self::APP_ERROR_CODE['LOCATION_ERROR_CODE'] => 404,
    ];

    public function __construct(
        string $appErrorCode,
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct($appErrorCode, $message, $previous, $headers, $code);
    }

    /**
     * @param string $appErrorCode
     * @param Exception|null $previous
     *
     * @return int
     */
    protected function getSsoStatusCode(string $appErrorCode, Exception $previous = null) : int
    {
        return self::$locationErrorHttpStatusCode[$appErrorCode];
    }
}
