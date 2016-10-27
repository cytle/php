<?php

namespace HessianService59\Providers;

use Illuminate\Support\Facades\Facade;

class HessianServiceFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hessianService';
    }
}
