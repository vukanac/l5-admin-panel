<?php

namespace App;

/**
* 
*/
class ActionCommandFactory
{
    public static function create($name, $params)
    {
        if (class_exists($name)) {
            return (new \ReflectionClass($name))->newInstanceArgs([$params]);
            //return new $name($id);
        } else {
            throw new \Exception("Invalid Action given.");
        }
    }
}
