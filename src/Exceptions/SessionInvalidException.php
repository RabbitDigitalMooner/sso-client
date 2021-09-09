<?php

namespace App\Exceptions;

use Exception;

class SessionInvalidException extends ApplicationHttpException
{
    public const SESSION_ERROR_CODE = [
        'SESSION_INVALID' => 'session_expired_or_invalid',
    ];

    protected static array $sessionErrorHttpStatusCode = [
        'session_expired_or_invalid' => 401,
    ];

    public function __construct(
        $message = null,
        $errors = null,
        ?Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(
            self::SESSION_ERROR_CODE['SESSION_INVALID'],
            $message,
            $errors,
            $previous,
            $headers,
            $code
        );
    }

    protected function getSsoStatusCode(string $sessionErrorCode, ?Exception $previous = null) : int
    {
        return self::$sessionErrorHttpStatusCode[$sessionErrorCode];
    }
}
