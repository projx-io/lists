<?php

namespace ProjxIO\Lists;

use Closure;

abstract class Reductions
{
    public static function initial(&$count, $ignore_initial_null, &$current, $a, $b, $increment = true)
    {
        if (!$count && $ignore_initial_null && $current === null) {
            $current = $b;
            if ($increment) {
                $count++;
            }
        } else {
            $current = $a;
            $count++;
        }
    }

    /**
     * Sums an array.
     *
     * @param callable|null $map
     * @param boolean $ignore_initial_null
     * @return Closure
     */
    public static function sum(callable $map = null, $ignore_initial_null = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial_null) {
            self::initial($count, $ignore_initial_null, $current, $current, 0);
            return $current + (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    /**
     * Products an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial_null
     * @return Closure
     */
    public static function product(callable $map = null, $ignore_initial_null = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial_null) {
            self::initial($count, $ignore_initial_null, $current, $current, 1);
            return $current * (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    /**
     * Merges an array.
     *
     * @param callable|null $map
     * @return Closure
     */
    public static function merge(callable $map = null, $ignore_initial_null = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial_null) {
            self::initial($count, $ignore_initial_null, $current, $current, []);
            return array_merge($current, (is_callable($map) ? call_user_func($map, $value, $key) : $value));
        };
    }

    /**
     * Implodes an array.
     *
     * @param string $glue
     * @param callable|null $map
     * @param bool $ignore_initial_null
     * @return Closure
     */
    public static function implode($glue = '', callable $map = null, $ignore_initial_null = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, $glue, &$count, $ignore_initial_null) {
            self::initial($count, $ignore_initial_null, $current, $current . $glue, '');
            return $current . (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    /**
     * Performs a logical and on each item in an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial_null
     * @return Closure
     */
    public static function ands(callable $map = null, $ignore_initial_null = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial_null) {
            self::initial($count, $ignore_initial_null, $current, $current, true);
            return $current && (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    /**
     * Performs a logical or on each item in an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial_null
     * @return Closure
     */
    public static function ors(callable $map = null, $ignore_initial_null = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial_null) {
            self::initial($count, $ignore_initial_null, $current, $current, false);
            return $current || (is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    /**
     * Finds the max of an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial_null
     * @return Closure
     */
    public static function max(callable $map = null, $ignore_initial_null = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial_null) {
            self::initial($count, $ignore_initial_null, $current, $current, $value);
            return max($current, (is_callable($map) ? call_user_func($map, $value, $key) : $value));
        };
    }

    /**
     * Finds the min of an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial_null
     * @return Closure
     */
    public static function min(callable $map = null, $ignore_initial_null = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial_null) {
            self::initial($count, $ignore_initial_null, $current, $current, $value);
            return min($current, (is_callable($map) ? call_user_func($map, $value, $key) : $value));
        };
    }

    /**
     * Finds the average of an array.
     *
     * @param callable|null $map
     * @param bool $ignore_initial_null
     * @return Closure
     */
    public static function average(callable $map = null, $ignore_initial_null = true)
    {
        $count = 0;
        return function ($current, $value, $key) use ($map, &$count, $ignore_initial_null) {
            self::initial($count, $ignore_initial_null, $current, $current, 0, false);
            return ($current * ($count) + (is_callable($map) ? call_user_func($map, $value, $key) : $value)) / ($count + 1);
        };
    }
}
