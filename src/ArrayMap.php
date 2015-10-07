<?php

namespace ProjxIO\Lists;

use ArrayIterator;
use Traversable;

class ArrayMap extends AbstractArray implements Map
{
    /**
     * @param $array
     * @return Map
     */
    protected function wrap(&$array)
    {
        return new ArrayMap($array);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Map
     */
    public function __set($key, $value)
    {
        return $this->put($key, $value);
    }

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return $this->toObject();
    }
}
