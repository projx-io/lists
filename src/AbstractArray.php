<?php

namespace ProjxIO\Lists;

abstract class AbstractArray extends AbstractList
{
    /**
     * @var array
     */
    protected $items;

    /**
     * @param array $items
     */
    public function __construct($items = [])
    {
        if ($items instanceof Collection) {
            $items = $items->items();
        }

        $this->items = (array)$items;
    }

    /**
     * @return array
     */
    public function items()
    {
        return $this->items;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    protected function add($value)
    {
        $this->items[] = $value;
        return $value;
    }

    /**
     * @param mixed $value
     * @return boolean
     */
    public function contains($value)
    {
        return in_array($value, $this->items(), true);
    }

    /**
     * @return Set
     */
    public function keys()
    {
        return new ArraySet(array_keys($this->items));
    }

    /**
     * @return Set
     */
    public function values()
    {
        return new ArraySet(array_values($this->items));
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Map
     */
    public function put($key, $value)
    {
        $this->items[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function has($key)
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->items[$key];
    }

    /**
     * @param string $key
     * @return Map
     */
    public function remove($key)
    {
        unset($this->items[$key]);
        return $this;
    }
}
