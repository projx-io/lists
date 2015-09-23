<?php

namespace ProjxIO\Lists;

interface Vector
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function add($value);

    /**
     * @param mixed $value
     * @return mixed
     */
    public function contains($value);

    /**
     * @param mixed $value
     * @return mixed
     */
    public function remove($value);
}
