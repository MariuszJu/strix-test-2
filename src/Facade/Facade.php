<?php

namespace RockSolidSoftware\StrixTest\Facade;

abstract class Facade
{

    /**
     * @param string $name
     * @param array  $arguments
     * @return mixed
     */
    public static function __callStatic($name, array $arguments = [])
    {
        return is_callable($callback = [static::getService(), $name])
            ? forward_static_call_array($callback, $arguments)
            : null;
    }

}
