[![Build Status](https://travis-ci.org/projx-io/lists.svg)](https://travis-ci.org/projx-io/lists)

# ProjxIO\Lists

Maps: key based arrays
Sets: index based arrays

## globals

### map($array=[])

returns an instance of a `Map`.

### set($array=[])

returns an instance of a `Set`.

## Arrays

The `Arrays` class is an `abstract class` whose methods:

- are `public static`
- take in a reference to an array
- take in at least one callable value, or an array of callable values
- return a new array
 - each will not return an array, and will iterate over the original, passing values by-ref, and modifying the original 
   element

### each

    null each(array &$array, callable $callback, $keys = true)
    array map(array &$array, callable $callback, $limit = -1, $keys = true)
    array filter(array &$array, callable $callback, $limit = -1, $keys = true)
    array mapFilter(array &$array, callable $map, callable $filter, $limit = -1, $keysMap = true, $keysFilter = true)
    array rename(array &$array, callable $callback, $keys = true)
    array group(array &$array, callable $callback, $keys = true)
    array groups(array &$array, array $callbacks, $keys = true)
    array reduce(array &$array, callable $callback, $initial = null)
    array first(array &$array, callable $filter = null)
    array sort(array &$array, callable $map = null)
    array reorder(array &$values, array &$keys)
    array combine(array &$keys, array &$values)

## Maps

    callable not(callable $callback)
    callable size(callable $map = null)
    callable toSet()
    callable toMap()
    callable has($param1 = null, $param2 = null, $param3 = null)
    callable get($param1 = null, $param2 = null, $param3 = null)
    callable invoke() # ?
    callable regex($pattern)
    callable parse($pattern)
    callable ands(callable $param1 = null, callable $param2 = null, callable $param3 = null)
    callable ors(callable $param1 = null, callable $param2 = null, callable $param3 = null)
    callable isString()
    callable isArray()
    callable isObject()
    callable isNumeric()
    callable isInteger()
    callable isBoolean()
    callable isTrue()
    callable isTruthy()
    callable isFalse()
    callable isFalsey()
    callable equals($expect)
    callable atLeast($expect)
    callable atMost($expect)
    callable moreThan($expect)
    callable lessThan($expect)
    callable key()
    callable isValueOf($array)
    callable isKeyOf($array)
    callable reduce($reduction, $initial = null)
    callable offset($offset)
    callable scale($scale)
    callable power($power)
    callable log($base)
    callable round($precision = 0, $mode = PHP_ROUND_HALF_UP)
    callable floor()
    callable ceil()
    callable mod($divisor)

## Reductions

    callable sum(callable $map = null, $ignore_initial_null = true)
    callable product(callable $map = null, $ignore_initial_null = true)
    callable merge(callable $map = null, $ignore_initial_null = true)
    callable implode($glue = '', callable $map = null, $ignore_initial_null = true)
    callable ands(callable $map = null, $ignore_initial_null = true)
    callable ors(callable $map = null, $ignore_initial_null = true)
    callable max(callable $map = null, $ignore_initial_null = true)
    callable min(callable $map = null, $ignore_initial_null = true)
    callable average(callable $map = null, $ignore_initial_null = true)
