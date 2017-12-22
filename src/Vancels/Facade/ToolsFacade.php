<?php
namespace Vancels\Tools\Facade;

class ToolsFacade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'tools';
    }
}
