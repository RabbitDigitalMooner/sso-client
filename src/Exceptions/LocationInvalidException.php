<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;

class LocationInvalidException extends ApplicationHttpException
{
    public const LOCATION_ERROR_CODE = [
        'COUNTRY_INVALID'  => 'country_not_found',
        'PROVINCE_INVALID' => 'province_not_found',
        'DISTRICT_INVALID' => 'district_not_found',
    ];

    protected static array $locationErrorHttpStatusCode = [
        self::LOCATION_ERROR_CODE['LOCATION_ERROR_CODE']  => 404,
        self::LOCATION_ERROR_CODE['PROVINCE_INVALID']     => 404,
        self::LOCATION_ERROR_CODE['DISTRICT_INVALID']     => 404,
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
     * {@inheritdoc}
     */
    protected function getSsoStatusCode(string $locationErrorCode, Exception $previous = null) : int
    {
        return self::$locationErrorHttpStatusCode[$locationErrorCode];
    }
}
