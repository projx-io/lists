<?php

namespace ProjxIO\Lists;

use ArrayAccess;
use JsonSerializable;

interface Map extends Container, Collection, Sortable, KeySortable, JsonSerializable
{
    /**
     * @return array
     */
    public function toObject();
}
