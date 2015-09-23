<?php

namespace ProjxIO\Lists;

use Countable;
use IteratorAggregate;

interface Collection extends IteratorAggregate, Countable
{
    /**
     * @param callable $callback
     * @return $this
     */
    public function each(callable $callback);

    /**
     * @param callable $callback
     * @return $this
     */
    public function map(callable $callback);

    /**
     * @param callable $callback
     * @return $this
     */
    public function filter(callable $callback);

    /**
     * @param callable $map
     * @param callable $filter
     * @return $this
     */
    public function mapFilter(callable $map, callable $filter);

    /**
     * @param mixed $initial
     * @param callable $callback
     * @return mixed
     */
    public function reduce($initial, callable $callback);

    /**
     * @param callable $callback
     * @return Map
     */
    public function rename(callable $callback);

    /**
     * @param callable $callback
     * @return Map
     */
    public function group(callable $callback);

    /**
     * @param callable $filter
     * @return mixed
     */
    public function first(callable $filter = null);

    /**
     * @param Collection|array $collection
     * @return $this
     */
    public function merge($collection);

    /**
     * @return array|object
     */
    public function items();
}
