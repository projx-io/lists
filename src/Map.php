<?php

namespace ProjxIO\Lists;

use ArrayAccess;
use JsonSerializable;

interface Map extends Container, Collection, Sortable, JsonSerializable
{
    /**
     * @return array
     */
    public function toObject();
}
