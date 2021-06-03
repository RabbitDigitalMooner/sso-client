<?php

namespace RabbitDigital\SsoClient;

use Illuminate\Support\Facades\Facade;

class SsoClientFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() : string
    {
        return 'SsoClient';
    }
}
