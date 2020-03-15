<?php
namespace App\Providers\Logger\Facade;

use Illuminate\Support\Facades\Facade;

class AppLogger extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'logger';
    }
}