<?php

namespace ProjxIO\Lists;

use ArrayAccess;
use JsonSerializable;

interface Set extends Vector, Collection, ArrayAccess, Sortable, JsonSerializable
{
    /**
     * @return array
     */
    public function toArray();
}
