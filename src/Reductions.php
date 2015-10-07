<?php

namespace ProjxIO\Lists;

use Closure;

abstract class Reductions
{
    /**
     * Sums an array.
     *
     * @param callable|null $map
     * @param boolean $ignore_initial
     * @return Closure
     */
    public static function sum(callable $map = null, $ignore_initial = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial) {
            $current = !$count++ && $ignore_initial && $current === null ? 0 : $current;
            return $current + (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    /**
     * Products an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial
     * @return Closure
     */
    public static function product(callable $map = null, $ignore_initial = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial) {
            $current = !$count++ && $ignore_initial && $current === null ? 1 : $current;
            return $current * (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    /**
     * Merges an array.
     *
     * @param callable|null $map
     * @return Closure
     */
    public static function merge(callable $map = null, $ignore_initial = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial) {
            $current = !$count++ && $ignore_initial && $current === null ? [] : $current;
            return array_merge($current, (is_callable($map) ? call_user_func($map, $value, $key) : $value));
        };
    }

    /**
     * Implodes an array.
     *
     * @param string $glue
     * @param callable|null $map
     * @param bool $ignore_initial
     * @return Closure
     */
    public static function implode($glue = '', callable $map = null, $ignore_initial = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, $glue, &$count, $ignore_initial) {
            $current = !$count++ && $ignore_initial && $current === null ? '' : $current . $glue;
            return $current . (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    /**
     * Performs a logical and on each item in an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial
     * @return Closure
     */
    public static function ands(callable $map = null, $ignore_initial = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial) {
            $current = !$count++ && $ignore_initial && $current === null ? true : $current;
            return $current && (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    /**
     * Performs a logical or on each item in an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial
     * @return Closure
     */
    public static function ors(callable $map = null, $ignore_initial = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial) {
            $current = !$count++ && $ignore_initial && $current === null ? false : $current;
            return $current || (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    /**
     * Finds the max of an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial
     * @return Closure
     */
    public static function max(callable $map = null, $ignore_initial = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial) {
            $current = !$count++ && $ignore_initial && $current === null ? $value : $current;
            return max($current, (is_callable($map) ? call_user_func($map, $value, $key) : $value));
        };
    }

    /**
     * Finds the min of an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial
     * @return Closure
     */
    public static function min(callable $map = null, $ignore_initial = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial) {
            $current = !$count++ && $ignore_initial && $current === null ? $value : $current;
            return min($current, (is_callable($map) ? call_user_func($map, $value, $key) : $value));
        };
    }

    /**
     * Finds the average of an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial
     * @return Closure
     */
    public static function average(callable $map = null, $ignore_initial = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial) {
            $current = !$count++ && $ignore_initial && $current === null ? [] : $current;
            return ($current * ($count - 1) + (is_callable($map) ? call_user_func($map, $value, $key) : $value)) / $count;
        };
    }
}
