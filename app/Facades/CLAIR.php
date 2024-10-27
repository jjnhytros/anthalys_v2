<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CLAIR extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'clair';
    }

    public static function comprehension()
    {
        return app('clair.comprehension');
    }

    public static function learning()
    {
        return app('clair.learning');
    }

    public static function adaptation()
    {
        return app('clair.adaptation');
    }

    public static function integration()
    {
        return app('clair.integration');
    }

    public static function resilience()
    {
        return app('clair.resilience');
    }
}
