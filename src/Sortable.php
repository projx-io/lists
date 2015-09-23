<?php

namespace ProjxIO\Lists;

interface Sortable
{
    /**
     * @param int $flags
     * @return Vector
     */
    public function sort($flags = 0);
}
