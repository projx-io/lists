<?php

namespace ProjxIO\Lists;

class ArraySet extends AbstractArray implements Set
{
    /**
     * @param $array
     * @return Set
     */
    protected function wrap(&$array)
    {
        return new ArraySet($array);
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    public function contains($value)
    {
        return parent::contains($value);
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
        return $this->toArray();
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function add($value)
    {
        return parent::add($value);
    }
}
