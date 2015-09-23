<?php

namespace ProjxIO\Lists;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

abstract class AbstractArray implements ArrayAccess, IteratorAggregate, Countable
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @param array $items
     */
    public function __construct($items = [])
    {
        if ($items instanceof Collection) {
            $items = $items->items();
        }

        $this->items = (array)$items;
    }

    /**
     * @param $array
     * @return Set|Map
     */
    abstract protected function wrap(&$array);

    /**
     * @param mixed $value
     * @return mixed
     */
    protected function add($value)
    {
        $this->items[] = $value;
        return $value;
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    public function contains($value)
    {
        return in_array($value, $this->items(), true);
    }

    /**
     * @return Set
     */
    public function keys()
    {
        return new ArraySet(array_keys($this->items));
    }

    /**
     * @return Set
     */
    public function values()
    {
        return new ArraySet(array_values($this->items));
    }

    /**
     * @param callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        Arrays::each($this->items, $callback);
        return $this;
    }

    /**
     * @param callable $callback
     * @param int $limit
     * @return $this
     */
    public function map(callable $callback, $limit = -1)
    {
        $array = Arrays::map($this->items, $callback, $limit);
        return $this->wrap($array);
    }

    /**
     * @param callable $callback
     * @param int $limit
     * @return $this
     */
    public function filter(callable $callback, $limit = -1)
    {
        $array = Arrays::filter($this->items, $callback, $limit);
        return $this->wrap($array);
    }

    /**
     * @param callable $map
     * @param callable $filter
     * @param int $limit
     * @return $this
     */
    public function mapFilter(callable $map, callable $filter, $limit = -1)
    {
        $array = Arrays::mapFilter($this->items, $map, $filter, $limit);
        return $this->wrap($array);
    }

    /**
     * @param mixed $initial
     * @param callable $callback
     * @return mixed
     */
    public function reduce($initial, callable $callback)
    {
        return Arrays::reduce($this->items, $initial, $callback);
    }

    /**
     * @param callable $callback
     * @return Map
     */
    public function rename(callable $callback)
    {
        return new ArrayMap(Arrays::rename($this->items, $callback));
    }

    /**
     * @param callable $callback
     * @return Map
     */
    public function group(callable $callback)
    {
        return new ArrayMap(Arrays::group($this->items, $callback));
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Map
     */
    public function put($key, $value)
    {
        $this->items[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->items[$key];
    }

    /**
     * @param string $key
     * @return Map
     */
    public function remove($key)
    {
        unset($this->items[$key]);
        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->put($offset, $value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * @param int $flags
     * @return $this
     */
    public function sort($flags = null)
    {
        $items = $this->items;
        $flags & SORT_DESC ? arsort($items, $flags) : asort($items, $flags);
        return $this->wrap($items);
    }

    /**
     * @param int $flags
     * @return $this
     */
    public function ksort($flags = 0)
    {
        $items = $this->items;
        $flags & SORT_DESC ? krsort($items, $flags) : ksort($items, $flags);
        return $this->wrap($items);
    }

    /**
     * @param callable $filter
     * @return mixed
     */
    public function first(callable $filter = null)
    {
        return Arrays::first($this->items, $filter);
    }

    /**
     * @param Collection|array $items
     * @return $this
     */
    public function merge($items)
    {
        if ($items instanceof Collection) {
            $items = (array)$items->items();
        }

        $items = array_merge($this->items, $items);
        return $this->wrap($items);
    }
}
