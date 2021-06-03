<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;

class SessionInvalidException extends ApplicationHttpException
{
    const SESSION_ERROR_CODE = [
        'SESSION_INVALID' => 'session_expired_or_invalid',
    ];

    protected static array $sessionErrorHttpStatusCode = [
        'session_expired_or_invalid' => 401,
    ];

    public function __construct(
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(self::SESSION_ERROR_CODE['SESSION_INVALID'], $message, $previous, $headers, $code);
    }

    /**
     * {@inheritdoc}
     */
    protected function getSsoStatusCode(string $sessionErrorCode, Exception $previous = null) : int
    {
        return self::$sessionErrorHttpStatusCode[$sessionErrorCode];
    }
}
