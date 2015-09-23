<?php

namespace ProjxIO\Lists;

class Collections
{
    /**
     * @param $value
     * @return Set
     */
    public static function set($value)
    {
        if ($value instanceof Collection) {
            return $value;
        }

        return new ArraySet($value);
    }

    /**
     * @param $value
     * @return Map
     */
    public static function map($value)
    {
        if ($value instanceof Collection) {
            return $value;
        }

        return new ArrayMap($value);
    }
}
