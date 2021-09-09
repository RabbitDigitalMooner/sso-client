<?php

namespace App\Exceptions;

use Exception;

class UserHasBeenBlockedException extends ApplicationHttpException
{
    public function __construct(
        $message = null,
        $errors = null,
        ?Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        parent::__construct(
            ApplicationHttpException::APP_ERROR_CODE['USER_HAS_BEEN_BLOCKED'],
            $message,
            $errors,
            $previous,
            $headers,
            $code
        );
    }
}
