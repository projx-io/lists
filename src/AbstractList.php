<?php

namespace ProjxIO\Lists;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

abstract class AbstractList implements Container, IteratorAggregate, Countable
{
    /**
     * @param $array
     * @return Set|Map
     */
    abstract protected function wrap(&$array);

    /**
     * @return array
     */
    abstract protected function items();

    /**
     * @param callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        $items = $this->toArray();
        Arrays::each($items, $callback);
        return $this;
    }

    /**
     * @param callable $callback
     * @param int $limit
     * @return $this
     */
    public function map(callable $callback, $limit = -1)
    {
        $items = $this->toArray();
        $array = Arrays::map($items, $callback, $limit);
        return $this->wrap($array);
    }

    /**
     * @param callable $callback
     * @param int $limit
     * @return $this
     */
    public function filter(callable $callback, $limit = -1)
    {
        $items = $this->toArray();
        $array = Arrays::filter($items, $callback, $limit);
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
        $items = $this->toArray();
        $array = Arrays::mapFilter($items, $map, $filter, $limit);
        return $this->wrap($array);
    }

    /**
     * @param callable $callback
     * @param mixed|null $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        $items = $this->toArray();
        return Arrays::reduce($items, $callback, $initial);
    }

    /**
     * @param callable $callback
     * @return Map
     */
    public function rename(callable $callback)
    {
        $items = $this->toArray();
        return new ArrayMap(Arrays::rename($items, $callback));
    }

    /**
     * @param callable $callback
     * @return Map
     */
    public function group(callable $callback)
    {
        $items = $this->toArray();
        return new ArrayMap(Arrays::group($items, $callback));
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
        return new ArrayIterator($this->items());
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
        return count($this->items());
    }

    /**
     * @param callable|null $map
     * @return Vector
     */
    public function sort(callable $map = null)
    {
        $items = $this->toArray();
        $array = Arrays::sort($items, $map);
        return $this->wrap($array);
    }

    /**
     * @param callable $filter
     * @return mixed
     */
    public function first(callable $filter = null)
    {
        $items = $this->toArray();
        return Arrays::first($items, $filter);
    }

    /**
     * @param Collection|array $items
     * @return $this
     */
    public function merge($items)
    {
        if ($items instanceof Collection) {
            $items = (array)(array)$items->items();
        }

        $items = (array)array_merge($this->items(), $items);
        return $this->wrap($items);
    }


    /**
     * @return array
     */
    public function toArray()
    {
        return (array)$this->items();
    }

    /**
     * @return object
     */
    public function toObject()
    {
        return (object)$this->items();
    }
}
