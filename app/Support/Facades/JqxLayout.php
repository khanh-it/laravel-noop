<?php

namespace App\Support\Facades;

use App\Helpers\Jqx\Layout;

/**
 * 
 * @see \App\Helpers\Jqx\Layout
 */
class JqxLayout extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Layout::class;
    }
}
