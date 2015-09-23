<?php

namespace ProjxIO\Lists;

interface KeySortable
{
    /**
     * @param int $flags
     * @return Vector
     */
    public function ksort($flags = 0);
}
