<?php

namespace ProjxIO\Lists;

use ArrayAccess;
use Closure;

/**
 * This class provides methods that are lazy evaluated. All the methods return a callback that is to be used usually
 * when iterating through a set. The callbacks returned have two parameters: [value, key].
 *
 * The results that a method says it returns is actually the value that the returned callback will return.
 * For example:
 *      callback = Maps::size();
 *      a = callback(['x', 'y']);           // 2
 *      b = callback(['x', 'y', 'z']);      // 3
 *
 * Some callbacks will can use the key argument as well.
 * For example:
 *      callback = Maps::key();
 *      a = callback('x', 'y');             // 'y'
 *
 * Some methods will expect parameters when first called.
 * For example:
 *      callback = Maps::equals('z');
 *      a = callback('x', 'y');             // false
 *      b = callback('z', 'y');             // true
 *
 * Some methods will expect parameters that are other callbacks.
 * For example:
 *      callback = Maps::not(Maps::equals('z'));
 *      a = callback('x', 'y');             // true
 *      b = callback('z', 'y');             // false
 *
 * Some methods can use an arbitrary number of parameters.
 * For example:
 *      item = ['a' => ['b' => ['c' => 'd']]]
 *      callback = Maps::get();
 *      a = callback(item);         // ['a' => ['b' => ['c' => 'd']]]
 *
 *      callback = Maps::get('a');
 *      a = callback(item);         // ['b' => ['c' => 'd']]
 *
 *      callback = Maps::get('a', 'b');
 *      a = callback(item);         // ['c' => 'd']
 *
 *      callback = Maps::get('a', 'b', 'c');
 *      a = callback(item);         // 'd'
 *
 * @package ProjxIO\Lists
 */
abstract class Maps
{
    /**
     * Boolean negates the result of a callback.
     *
     * @param callable $callback
     * @return Closure
     */
    public static function not(callable $callback)
    {
        return function ($value, $key) use ($callback) {
            return !call_user_func($callback, $value, $key);
        };
    }

    /**
     * Returns the size of an array.
     *
     * @param callable|null $map
     * @return Closure
     */
    public static function size(callable $map = null)
    {
        return function ($value, $key) use ($map) {
            return count(is_callable($map) ? call_user_func($map, $value, $key) : $value);
        };
    }

    /**
     * Creates a new ArraySet with the value (which should be an array).
     *
     * @return Closure
     */
    public static function toSet()
    {
        return function ($value) {
            return new ArraySet($value);
        };
    }

    /**
     * Creates a new ArrayMap with the value (which should be an array or object).
     *
     * @return Closure
     */
    public static function toMap()
    {
        return function ($value) {
            return new ArrayMap($value);
        };
    }

    /**
     * Use an n number of keys to access a value n levels inside an array or object. If zero keys are provided, then
     * the initial value will be returned.
     *
     * @param string|integer|null [param1]
     * @param string|integer|null [param2]
     * @param string|integer|null [param3]
     * @return Closure
     */
    public static function get($param1 = null, $param2 = null, $param3 = null)
    {
        $args = func_get_args();

        return function ($value) use ($args) {
            foreach ($args as $arg) {
                if (is_object($value) && property_exists($value, $arg)) {
                    $value = $value->{$arg};
                } elseif ((is_array($value) || $value instanceof ArrayAccess) && isset($value[$arg])) {
                    $value = $value[$arg];
                } else {
                    throw new OutOfBoundsException("key {$arg} does not exist");
                }
            }

            return $value;
        };
    }

    /**
     * Use an n number of keys to find a value n levels inside an array or object. If zero keys are provided, then true
     * will be returned.
     *
     * @param string|integer|null $param1
     * @param string|integer|null $param2
     * @param string|integer|null $param3
     * @return Closure
     */
    public static function has($param1 = null, $param2 = null, $param3 = null)
    {
        $args = func_get_args();

        return function ($value) use ($args) {
            foreach ($args as $arg) {
                if (is_object($value) && property_exists($value, $arg)) {
                    $value = $value->{$arg};
                } elseif ((is_array($value) || $value instanceof ArrayAccess) && isset($value[$arg])) {
                    $value = $value[$arg];
                } else {
                    return false;
                }
            }

            return true;
        };
    }

    public static function invoke()
    {
        $args = func_get_args();
        return function ($callback) use ($args) {
            return call_user_func_array($callback, $args);
        };
    }

    /**
     * Runs a regex pattern on a value. Returns true or false depending on if the pattern matched.
     *
     * @param $pattern
     * @return Closure
     */
    public static function regex($pattern)
    {
        return function ($value) use ($pattern) {
            return preg_match($pattern, $value);
        };
    }

    /**
     * Runs a regex pattern on a value. Returns false if the pattern did not match, and an array containing the captured
     * data if it passes.
     *
     * @param $pattern
     * @return Closure
     */
    public static function parse($pattern)
    {
        return function ($value) use ($pattern) {
            $matches = [];
            return preg_match($pattern, $value, $matches) ? $matches : false;
        };
    }

    /**
     * @param callable|null $param1
     * @param callable|null $param2
     * @param callable|null $param3
     * @return Closure
     */
    public static function ands(callable $param1 = null, callable $param2 = null, callable $param3 = null)
    {
        $args = new ArraySet(func_get_args());

        return function ($value, $key) use ($args) {
            return $args->filter(Maps::not(Maps::invoke($value, $key)), 1)->count() === 0;
        };
    }

    /**
     * @param callable|null $param1
     * @param callable|null $param2
     * @param callable|null $param3
     * @return Closure
     */
    public static function ors(callable $param1 = null, callable $param2 = null, callable $param3 = null)
    {
        $args = new ArraySet(func_get_args());

        return function ($value, $key) use ($args) {
            return $args->filter(Maps::invoke($value, $key), 1)->count() > 0;
        };
    }

    /**
     * Returns bool value representing if a value is a string.
     *
     * @return Closure
     */
    public static function isString()
    {
        return function ($value, $key) {
            return is_string($value);
        };
    }

    /**
     * Returns bool value representing if a value is an array.
     *
     * @return Closure
     */
    public static function isArray()
    {
        return function ($value, $key) {
            return is_array($value);
        };
    }

    /**
     * Returns bool value representing if a value is an object.
     *
     * @return Closure
     */
    public static function isObject()
    {
        return function ($value, $key) {
            return is_object($value);
        };
    }

    /**
     * Returns bool value representing if a value is a numeric value (numbers in strings count).
     *
     * @return Closure
     */
    public static function isNumeric()
    {
        return function ($value, $key) {
            return is_numeric($value);
        };
    }

    /**
     * Returns bool value representing if a value is an integer.
     *
     * @return Closure
     */
    public static function isInteger()
    {
        return function ($value, $key) {
            return is_integer($value);
        };
    }

    /**
     * Returns bool value representing if a value is a boolean.
     *
     * @return Closure
     */
    public static function isBoolean()
    {
        return function ($value, $key) {
            return is_bool($value);
        };
    }

    /**
     * Returns bool value representing if a value is a true and only true.
     *
     * @return Closure
     */
    public static function isTrue()
    {
        return function ($value, $key) {
            return $value === true;
        };
    }

    /**
     * Returns bool value representing if a value is a truthy value.
     *
     * @return Closure
     */
    public static function isTruthy()
    {
        return function ($value, $key) {
            return !!$value;
        };
    }

    /**
     * Returns bool value representing if a value is false and only false.
     *
     * @return Closure
     */
    public static function isFalse()
    {
        return function ($value, $key) {
            return $value === false;
        };
    }

    /**
     * Returns bool value representing if a value is a falsey value.
     *
     * @return Closure
     */
    public static function isFalsey()
    {
        return function ($value, $key) {
            return !$value;
        };
    }

    /**
     * Returns bool value representing if a value matches an expected value.
     *
     * @return Closure
     */
    public static function equals($expect)
    {
        return function ($actual) use ($expect) {
            return $actual === $expect;
        };
    }

    /**
     * Returns bool value representing if a value is at least (GTE) an expected value.
     *
     * @return Closure
     */
    public static function atLeast($expect)
    {
        return function ($actual) use ($expect) {
            return $actual >= $expect;
        };
    }

    /**
     * Returns bool value representing if a value is at most (LTE) an expected value.
     *
     * @return Closure
     */
    public static function atMost($expect)
    {
        return function ($actual) use ($expect) {
            return $actual <= $expect;
        };
    }

    /**
     * Returns bool value representing if a value is more than (GT) an expected value.
     *
     * @return Closure
     */
    public static function moreThan($expect)
    {
        return function ($actual) use ($expect) {
            return $actual > $expect;
        };
    }

    /**
     * Returns bool value representing if a value is less than (LT) an expected value.
     *
     * @return Closure
     */
    public static function lessThan($expect)
    {
        return function ($actual) use ($expect) {
            return $actual < $expect;
        };
    }

    /**
     * Returns the key provided.
     *
     * @return Closure
     */
    public static function key()
    {
        return function ($value, $key) {
            return $key;
        };
    }

    /**
     * Returns bool is a value can be found in the provided array.
     *
     * @return Closure
     */
    public static function isValueOf($array, $strict = false)
    {
        $array = Collections::set($array);
        return function ($value) use ($array) {
            return $array->contains($value);
        };
    }

    /**
     * Returns bool is a value can be found as a key in the provided array.
     *
     * @return Closure
     */
    public static function isKeyOf($array)
    {
        $array = Collections::map($array);
        return function ($value) use ($array) {
            return $array->has($value);
        };
    }

    /**
     * Returns a reduction of the value, assuming the value is an array or collection.
     *
     * @param $reduction
     * @param null $initial
     * @return Closure
     */
    public static function reduce($reduction, $initial = null)
    {
        return function ($value, $key) use ($initial, $reduction) {
            $map = new ArrayMap($value);
            return $map->reduce($reduction, $initial);
        };
    }

    /**
     * Offsets a number.
     *
     * @param int|float $offset
     * @return Closure
     */
    public static function offset($offset)
    {
        return function ($value) use ($offset) {
            return $value + $offset;
        };
    }

    /**
     * Scales a number.
     *
     * @param int|float $scale
     * @return Closure
     */
    public static function scale($scale)
    {
        return function ($value) use ($scale) {
            return $value * $scale;
        };
    }

    /**
     * Raises a number by a power.
     *
     * @param int|float $power
     * @return Closure
     */
    public static function power($power)
    {
        return function ($value) use ($power) {
            return pow($value, $power);
        };
    }

    /**
     * Performs a logarithm on a number with a base.
     *
     * @param int|float $base
     * @return Closure
     */
    public static function log($base)
    {
        return function ($value) use ($base) {
            return log($value, $base);
        };
    }

    /**
     * Rounds a number.
     *
     * @param int $precision
     * @param int $mode
     * @return Closure
     */
    public static function round($precision = 0, $mode = PHP_ROUND_HALF_UP)
    {
        return function ($value) use ($precision, $mode) {
            return round($value, $precision, $mode);
        };
    }

    /**
     * Floors a number.
     *
     * @return Closure
     */
    public static function floor()
    {
        return function ($value) {
            return floor($value);
        };
    }

    /**
     * Ceils a number.
     *
     * @return Closure
     */
    public static function ceil()
    {
        return function ($value) {
            return ceil($value);
        };
    }

    /**
     * Mods a number.
     *
     * @param $divisor
     * @return Closure
     */
    public static function mod($divisor)
    {
        return function ($value) use ($divisor) {
            return $value % $divisor;
        };
    }
}
