<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApplicationHttpException extends HttpException
{
    public const APP_ERROR_CODE = [
        'EMPTY_ARGUMENT'                         => 'empty_argument',
        'INVALID_ARGUMENT'                       => 'invalid_argument',
        'CODE_NOT_FOUND'                         => 'code_not_found',
        'PHONE_INVALID_FOR_SMS'                  => 'phone_invalid_for_sms',
        'UNAUTHORIZED'                           => 'unauthorized',
        'UNVERIFIED_EMAIL'                       => 'unverified_email',
        'UNVERIFIED_PHONE'                       => 'unverified_phone',
        'USER_HAS_BEEN_BLOCKED'                  => 'user_has_been_blocked',
        'USER_LOCKED_BY_PIN'                     => 'user_locked_by_pin',
        'USER_LOCKED_BY_PASSWORD'                => 'user_locked_by_password',
        'PAGE_NOT_FOUND'                         => 'page_not_found',
        'USER_NOT_FOUND'                         => 'user_not_found',
        'EMAIL_NOT_FOUND'                        => 'email_not_found',
        'RABBIT_CARD_NOT_FOUND'                  => 'card_id_not_found',
        'REGISTRATION_TYPE_INVALID'              => 'registration_type_invalid',
        'REGISTRATION_SOURCE_INVALID'            => 'registration_source_invalid',
        'USER_EXISTS'                            => 'user_exists',
        'EMAIL_DUPLICATE'                        => 'email_duplicate',
        'RABBIT_CARD_DUPLICATE'                  => 'card_duplicate',
        'CODE_INVALID'                           => 'code_invalid',
        'TOO_MANY_ATTEMPTS'                      => 'too_many_attempts',
        'PASSWORD_SAME'                          => 'password_same',
        'PIN_SAME'                               => 'pin_same',
        'VALIDATION_ERROR'                       => 'validation_error',
        'ENDPOINT_INVALID'                       => 'endpoint_invalid',
        'EMAIL_NOT_SENT'                         => 'email_not_sent',
        'SMS_NOT_SENT'                           => 'sms_not_sent',
        'INTERNAL_ERROR'                         => 'internal_error',
    ];

    protected static array $appErrorHttpStatusCode = [
        self::APP_ERROR_CODE['EMPTY_ARGUMENT']                   => 400,
        self::APP_ERROR_CODE['INVALID_ARGUMENT']                 => 400,
        self::APP_ERROR_CODE['CODE_NOT_FOUND']                   => 400,
        self::APP_ERROR_CODE['PHONE_INVALID_FOR_SMS']            => 400,
        self::APP_ERROR_CODE['UNAUTHORIZED']                     => 401,
        self::APP_ERROR_CODE['UNVERIFIED_EMAIL']                 => 403,
        self::APP_ERROR_CODE['UNVERIFIED_PHONE']                 => 403,
        self::APP_ERROR_CODE['USER_HAS_BEEN_BLOCKED']            => 403,
        self::APP_ERROR_CODE['USER_LOCKED_BY_PIN']               => 403,
        self::APP_ERROR_CODE['USER_LOCKED_BY_PASSWORD']          => 403,
        self::APP_ERROR_CODE['PAGE_NOT_FOUND']                   => 404,
        self::APP_ERROR_CODE['USER_NOT_FOUND']                   => 404,
        self::APP_ERROR_CODE['EMAIL_NOT_FOUND']                  => 404,
        self::APP_ERROR_CODE['RABBIT_CARD_NOT_FOUND']            => 404,
        self::APP_ERROR_CODE['REGISTRATION_TYPE_INVALID']        => 422,
        self::APP_ERROR_CODE['REGISTRATION_SOURCE_INVALID']      => 422,
        self::APP_ERROR_CODE['USER_EXISTS']                      => 422,
        self::APP_ERROR_CODE['EMAIL_DUPLICATE']                  => 422,
        self::APP_ERROR_CODE['RABBIT_CARD_DUPLICATE']            => 422,
        self::APP_ERROR_CODE['CODE_INVALID']                     => 422,
        self::APP_ERROR_CODE['PASSWORD_SAME']                    => 422,
        self::APP_ERROR_CODE['PIN_SAME']                         => 422,
        self::APP_ERROR_CODE['VALIDATION_ERROR']                 => 422,
        self::APP_ERROR_CODE['ENDPOINT_INVALID']                 => 423,
        self::APP_ERROR_CODE['TOO_MANY_ATTEMPTS']                => 429,
        self::APP_ERROR_CODE['EMAIL_NOT_SENT']                   => 500,
        self::APP_ERROR_CODE['SMS_NOT_SENT']                     => 500,
        self::APP_ERROR_CODE['INTERNAL_ERROR']                   => 500,
    ];

    protected string $appErrorCode;

    /**
     * @param string $appErrorCode Application specified code from ApplicationHttpException::APP_ERROR_CODE
     */
    public function __construct(
        string $appErrorCode,
        $message = null,
        Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        $this->appErrorCode = $appErrorCode;

        $statusCode = $this->getSsoStatusCode($appErrorCode, $previous);
        $message    = $this->getSsoMessage($appErrorCode, $message);

        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    /**
     * @param string $appErrorCode
     * @param Exception|null $previous
     *
     * @return int
     */
    protected function getSsoStatusCode(string $appErrorCode, Exception $previous = null) : int
    {
        return self::$appErrorHttpStatusCode[$appErrorCode];
    }

    /**
     * @param string $appErrorCode
     * @param string|null $message
     *
     * @return string
     */
    protected function getSsoMessage(string $appErrorCode, string $message = null)
    {
        return $message ?? $appErrorCode;
    }

    /**
     * @return string
     */
    public function getAppErrorCode() : string
    {
        return $this->appErrorCode;
    }
}
