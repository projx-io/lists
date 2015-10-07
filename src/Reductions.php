<?php

namespace ProjxIO\Lists;

abstract class Reductions
{
    public static function sum(callable $map = null)
    {
        return function ($current, $value, $key) use ($map) {
            return $current + (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }
}