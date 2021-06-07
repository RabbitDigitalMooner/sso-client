<?php

namespace RabbitDigital\SsoClient\Exceptions;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ValidatorException extends HttpException
{
    private mixed $errors;

    public function __construct(
        string $message = null,
        Throwable $previous = null,
        int $code = 0,
        array $headers = []
    ) {
        parent::__construct(422, $message, $previous, $headers, $code);

        $this->errors = json_decode($message, true);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return Response
     */
    public function render() : Response
    {
        return response([
            'status_code' => 422,
            'error_code'  => 'validation_error',
            'errors'      => $this->errors,
        ], 422);
    }
}
