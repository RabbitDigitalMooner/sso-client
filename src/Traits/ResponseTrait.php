<?php

namespace RabbitDigital\SsoClient\Traits;

use Psr\Http\Message\ResponseInterface;

trait ResponseTrait
{
    /**
     * Convert HTTP response to Array
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    public function responseAsArray(ResponseInterface $response) : array
    {
        return json_decode($response->getBody(), true) ?? [];
    }
}
