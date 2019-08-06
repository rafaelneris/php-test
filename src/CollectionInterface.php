<?php

namespace Live\Collection;

/**
 * Collection interface
 *
 * @package Live\Collection
 */
interface CollectionInterface
{

    /**
     * Returns a value by index
     *
     * @param string $index
     * @param mixed $defaultValue
     * @return mixed
     */
    public function get(string $index, $defaultValue = null);

    /**
     * Adds a value to the collection
     *
     * @param string $index
     * @param mixed $value
     * @param integer $defaultIndexExpires
     * @return void
     */
    public function set(string $index, $value, $defaultIndexExpires);

    /**
     * Checks whether the collection has the given index
     *
     * @param string $index
     * @return boolean
     */
    public function has(string $index);

    /**
     * Returns the count of items in the collection
     *
     * @return integer
     */
    public function count(): int;

    /**
     * Cleans the collection
     *
     * Estou aqui para testar sua atenção. Remova-me.
     *
     * @return void
     */
    public function clean();

    /**
     * Data to Json
     * @return false|string
     * @throws \Exception
     */
    public function toJson();

    /**
     * @param string $index
     * @return bool
     */
    public function isIndexExpired(string $index);
}
