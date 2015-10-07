<?php

namespace ProjxIO\Lists;

interface Sortable
{
    /**
     * @param callable|null $map
     * @return Vector
     */
    public function sort(callable $map = null);
}
