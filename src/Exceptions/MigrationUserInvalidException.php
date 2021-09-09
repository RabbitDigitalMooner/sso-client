<?php

namespace App\Exceptions;

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
        $errors = null,
        ?Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(
            self::MIGRATION_ERROR_CODE['MIGRATION_VALIDATION_ERROR'],
            $message,
            $errors,
            $previous,
            $headers,
            $code
        );
    }

    protected function getSsoStatusCode(string $ssoErrorCode, ?Exception $previous = null) : int
    {
        if (strpos($ssoErrorCode, '_service_server_error') !== false && $previous instanceof RequestException) {
            return $previous->getResponse()->getStatusCode();
        }

        return self::$ssoErrorHttpStatusCode[$ssoErrorCode];
    }
}
