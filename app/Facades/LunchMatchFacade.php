<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class LunchMatchFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'LunchMatch';
    }
}