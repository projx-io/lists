<?php

namespace ProjxIO\Lists;

use ArrayAccess;

interface Container extends ArrayAccess
{
    /**
     * @param string $key
     * @return boolean
     */
    public function has($key);

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed $value
     * @return boolean
     */
    public function put($key, $value);

    /**
     * @param string $key
     * @return boolean
     */
    public function remove($key);

    /**
     * @return Collection
     */
    public function keys();

    /**
     * @return Collection
     */
    public function values();
}
