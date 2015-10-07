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

    public static function product(callable $map = null)
    {
        return function ($current, $value, $key) use ($map) {
            return $current * (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    public static function merge(callable $map = null)
    {
        return function ($current, $value, $key) use ($map) {
            return array_merge($current, (is_callable($map) ? call_user_func($map, $value, $key) : $value));
        };
    }

    public static function implode($glue = '', callable $map = null)
    {
        return function ($current, $value, $key) use ($map, $glue) {
            return $current . $glue . (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    public static function ands(callable $map = null)
    {
        return function ($current, $value, $key) use ($map) {
            return $current && (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    public static function ors(callable $map = null)
    {
        return function ($current, $value, $key) use ($map) {
            return $current || (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }
}
