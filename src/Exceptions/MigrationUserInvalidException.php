<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;
use GuzzleHttp\Exception\RequestException;

class MigrationUserInvalidException extends ApplicationHttpException
{
    public const MIGRATION_ERROR_CODE = [
        'MIGRATION_VALIDATION_ERROR' => 'migration_validation_error',
    ];

    protected static array $ssoErrorHttpStatusCode = [
        'migration_validation_error' => 422,
    ];

    public function __construct(
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(
            self::MIGRATION_ERROR_CODE['MIGRATION_VALIDATION_ERROR'],
            $message,
            $previous,
            $headers,
            $code
        );
    }

    protected function getSsoStatusCode(string $ssoErrorCode, ?Exception $previous = null) : int
    {
        if (str_contains($ssoErrorCode, '_service_server_error')
            && $previous instanceof RequestException
        ) {
            return $previous->getResponse()->getStatusCode();
        }

        return self::$ssoErrorHttpStatusCode[$ssoErrorCode];
    }
}
