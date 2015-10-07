<?php

use ProjxIO\Lists\ArrayMap;
use ProjxIO\Lists\ArraySet;
use ProjxIO\Lists\Collection;
use ProjxIO\Lists\Map;
use ProjxIO\Lists\Set;

/**
 * @param $array
 * @return Map
 */
function map($array = [])
{
    if ($array instanceof Collection) {
        $array = $array->items();
    }

    return new ArrayMap($array);
}

/**
 * @param $array
 * @return Set
 */
function set($array = [])
{
    if ($array instanceof Collection) {
        $array = $array->items();
    }

    return new ArraySet($array);
}
