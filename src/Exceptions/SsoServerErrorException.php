<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;
use GuzzleHttp\Exception\RequestException;

class SsoServerErrorException extends ApplicationHttpException
{
    const SSO_ERROR_CODE = [
        'SSO_SERVICE_UNKNOWN_ERROR'               => 'sso_service_unknown_error',
        'SSO_AUTH_SERVICE_SERVER_ERROR'           => 'sso_auth_service_server_error',
        'SSO_AUTH_SERVICE_UNAVAILABLE'            => 'sso_auth_service_unavailable',
        'SSO_BACKOFFICE_SERVICE_UNAVAILABLE'      => 'sso_backoffice_service_unavailable',
        'SSO_LOCATION_SERVICE_SERVER_ERROR'       => 'sso_location_service_server_error',
        'SSO_LOCATION_SERVICE_UNAVAILABLE'        => 'sso_location_service_unavailable',
    ];

    protected static array $ssoErrorHttpStatusCode = [
        'sso_service_unknown_error'               => 500,
        'sso_auth_service_server_error'           => 500,
        'sso_auth_service_unavailable'            => 503,
        'sso_backoffice_service_unavailable'      => 503,
        'sso_location_service_server_error'       => 500,
        'sso_location_service_unavailable'        => 503,
    ];

    public function __construct(
        string $ssoErrorCode,
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct($ssoErrorCode, $message, $previous, $headers, $code);
    }

    /**
     * {@inheritdoc}
     */
    protected function getSsoStatusCode(string $ssoErrorCode, Exception $previous = null) : int
    {
        if (str_contains($ssoErrorCode, '_service_server_error') && $previous instanceof RequestException) {
            return $previous->getResponse()->getStatusCode();
        }

        return self::$ssoErrorHttpStatusCode[$ssoErrorCode];
    }
}
