<?php

namespace M3assy\LaravelAnnotations\Facades;

use Illuminate\Support\Facades\Facade;

class Annotation extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel_annotation';
    }
}
