<?php

namespace ProjxIO\Lists;

/**
 * An abstract class that provides helper methods for working with arrays.
 *
 * Many of the methods in this class may have one or more of following parameters:
 * - arrays
 * - callbacks
 * - limits
 * - key flags
 *
 * == Arrays
 * The array parameter is byref, but no changes to the original array itself will be made. The byref is to save system
 * resources by avoiding the cloning of the array.
 *
 * == Callbacks
 * Callbacks must be a valid callable value, in the form of a Closure, array, or string. In most cases the value itself
 * will not be provided as byref. The only exception currently is each().
 *
 * == Limits
 * Many of the methods will utilize a limit argument. The limit will stop a loop when the size of an array has reached
 * the limit. For an unlimited array, use the default -1.
 *
 * == Key Flags
 * The arguments that will be provided can differ depending on the keys argument, which defaults to true.
 * - If the keys argument is true, then the callback will be provided [&value, key].
 * - If the keys argument is false, then the callback will be provided [&value].
 * The reason for this is that there are some php methods that will throw an error if more than one argument is
 * provided. strtolower and strtoupper are a few examples.
 * @package ProjxIO\Lists
 */
abstract class Arrays
{
    /**
     * Invokes the provided callback for each element in the array.
     *
     * The value IS passed as a reference. If the callback accepts the value as a reference, the original value WILL
     * be modified if the value is changed. If this behavior is not desired, do not declare the value parameter in the
     * callback as a reference.
     *
     * If at anytime it is desired to stop the forEach loop, then have the callback return false. If anything other than
     * false (even other falsey values, i.e. null, 0, ''), then the loop will continue.
     *
     * For info on $keys, check class documentation for {@see ProjxIO\Lists\Arrays}
     *
     * @param array $array
     * @param callable $callback
     * @param bool $keys
     */
    public static function each(array &$array, callable $callback, $keys = true)
    {
        foreach ($array as $key => &$value) {
            if (call_user_func_array($callback, $keys ? [&$value, $key] : [&$value]) === false) {
                break;
            }
        }
    }

    /**
     * Returns a new array that contains the mapped values of the callback onto the original array.
     *
     * For info on $limit, check class documentation for {@see ProjxIO\Lists\Arrays}
     * For info on $keys, check class documentation for {@see ProjxIO\Lists\Arrays}
     *
     * @param array $array
     * @param callable $callback
     * @param int $limit
     * @param bool $keys
     * @return array
     */
    public static function map(array &$array, callable $callback, $limit = -1, $keys = true)
    {
        $result = [];
        self::each($array, function (&$value, $key) use (&$result, $callback, &$limit) {
            $result[$key] = call_user_func($callback, $value, $key);
            return count($result) !== $limit;
        }, $keys);
        return $result;
    }

    /**
     * Returns a new array that contains the values from the original array filtered by the callback.
     *
     * To keep a value, the returned value from the callback must be truthy. Any value that returns falsey will be
     * left out.
     *
     * For info on $limit, check class documentation for {@see ProjxIO\Lists\Arrays}
     * For info on $keys, check class documentation for {@see ProjxIO\Lists\Arrays}
     *
     * @param array $array
     * @param callable $callback
     * @param int $limit
     * @param bool $keys
     * @return array
     */
    public static function filter(array &$array, callable $callback, $limit = -1, $keys = true)
    {
        $result = [];
        self::each($array, function (&$value, $key) use (&$result, $callback, &$limit) {
            if (call_user_func($callback, $value, $key)) {
                $result[$key] = &$value;
            }
            return count($result) !== $limit;
        }, $keys);
        return $result;
    }

    /**
     * Returns a new array that contains the values from the original array filtered by the callback after being mapped.
     *
     * Note: The values returned are the original values. The map callback is only to help in combination with filter.
     * This method is extremely helpful to keep separation of concerns among mapping methods, as well as not having to
     * declare new sets to work with.
     *
     * To keep a value, the returned value from the filter callback must be truthy. Any value that returns falsey
     * will be left out.
     *
     * For info on $limit, check class documentation for {@see ProjxIO\Lists\Arrays}
     * For info on $keys*, check class documentation for {@see ProjxIO\Lists\Arrays}
     *
     * @param array $array
     * @param callable $map
     * @param callable $filter
     * @param int $limit
     * @param bool $keysMap
     * @param bool $keysFilter
     * @return array
     */
    public static function mapFilter(
        array &$array,
        callable $map,
        callable $filter,
        $limit = -1,
        $keysMap = true,
        $keysFilter = true
    )
    {
        return self::filter($array, function ($value, $key) use ($map, $filter, $limit, $keysMap, $keysFilter) {
            $mapped = call_user_func_array($map, $keysMap ? [$value, $key] : [$value]);
            return call_user_func_array($filter, $keysFilter ? [$mapped, $key] : [$mapped]);
        });
    }

    /**
     * Returns a new array that contains the original values, but with new keys.
     *
     * Think of this as a map, but the keys are mapped instead of the values.
     *
     * Warning: Items will be left out if the names are not unique.
     *
     * For info on $keys, check class documentation for {@see ProjxIO\Lists\Arrays}
     *
     * @param array $array
     * @param callable $callback
     * @param bool $keys
     * @return array
     */
    public static function rename(array &$array, callable $callback, $keys = true)
    {
        $keys = self::map($array, $callback, -1, $keys);
        return count($array) ? array_combine($keys, $array) : [];
    }

    /**
     * Maps each original value into a group, and returns an array containing the group names and values.
     *
     * This is similar to rename, except that the mapped names point to arrays of items that share the same group name,
     * rather than a single item.
     *
     * For info on $keys, check class documentation for {@see ProjxIO\Lists\Arrays}
     *
     * @param array $array
     * @param callable $callback
     * @param bool $keys
     * @return array
     */
    public static function group(array &$array, callable $callback, $keys = true)
    {
        $result = [];
        self::each($array, function (&$value, $key) use (&$result, $callback, $keys) {
            $group = call_user_func_array($callback, $keys ? [$value, $key] : [$value]);
            if (!array_key_exists($group, $result)) {
                $result[$group] = [];
            }
            $result[$group][$key] = &$value;
        });
        return $result;
    }

    /**
     * Standard reduce method. Use an aggregate method to obtain a single value computed from an array of items.
     *
     * Reduce invokes a callback for each item in an array. A result from a callback will be provided as a value to
     * the next callback. The result from the last callback will be the result for the reduce. The first callback will
     * receive the initial value that is supplied. If there are on items in the array, then the initial value will
     * be returned.
     *
     * @param array $array
     * @param mixed $initial
     * @param callable $callback
     * @return mixed
     */
    public static function reduce(array &$array, $initial, callable $callback)
    {
        $result = $initial;
        self::each($array, function ($value, $key) use (&$result, $callback) {
            $result = call_user_func($callback, $result, $value, $key);
        });
        return $result;
    }

    /**
     * Returns the first value in an array. If a filter callback is provided, then the first value to pass the filter
     * will be returned.
     *
     * @param array $array
     * @param callable $filter
     * @return mixed
     */
    public static function first(array &$array, callable $filter = null)
    {
        $filter = $filter ?: function () {
            return true;
        };

        $values = array_values(self::filter($array, $filter, 1));
        return array_shift($values);
    }
}
