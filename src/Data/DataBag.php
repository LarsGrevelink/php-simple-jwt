<?php

namespace LGrevelink\SimpleJWT\Data;

use JsonSerializable;

final class DataBag implements JsonSerializable
{
    /**
     * The contents of the bag.
     *
     * @var array
     */
    protected $items;

    /**
     * Constructor.
     *
     * @param array $items (optional)
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * Returns all items inside the bag.
     *
     * @return array
     */
    public function all()
    {
        return $this->items;
    }

    /**
     * Returns a specific item in the bag. If the item does not exists, the
     * default value is returned.
     *
     * @param string $name
     * @param mixed|null $default (optional)
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        if ($this->has($name)) {
            return $this->items[$name];
        }

        return $default;
    }

    /**
     * Returns whether the item is present in the bag.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name)
    {
        return array_key_exists($name, $this->items);
    }

    /**
     * Puts an item in the bag.
     *
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, $value)
    {
        $this->items[$name] = $value;
    }

    /**
     * Returns the data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->all();
    }
}
