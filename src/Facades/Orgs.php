<?php

namespace Dicibi\Orgs\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static hello(): string
 */
class Orgs extends Facade
{

    protected static function getFacadeAccessor(): string
    {
        return 'orgs';
    }

}