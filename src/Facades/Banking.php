<?php

namespace Kanexy\Banking\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kanexy\Banking\Banking
 */
class Banking extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Kanexy\Banking\Banking::class;
    }
}
