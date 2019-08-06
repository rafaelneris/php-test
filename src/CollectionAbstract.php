<?php


namespace Live\Collection;

use DateTime;

/**
 * Class CollectionAbstract
 * @package Live\Collection
 */
abstract class CollectionAbstract implements CollectionInterface
{

    /** @var int */
    const DEFAULT_INDEX_EXPIRE = 5;

    /**
     * Collection data
     *
     * @var array
     */
    protected $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [];
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function get(string $index, $defaultValue = null)
    {
        if (!$this->has($index)) {
            return $defaultValue;
        }

        if (!$this->isIndexExpired($index)) {
            return [];
        }

        return $this->data[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value, $indexExpiresSeconds = self::DEFAULT_INDEX_EXPIRE)
    {
        $expiresDateTime = (new DateTime())
            ->modify('+'.$indexExpiresSeconds.' seconds')
            ->format('Y-m-d H:i:s');
        $this->data[$index] = ['value' => $value, 'expires' => $expiresDateTime];
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
        return array_key_exists($index, $this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        $this->data = [];
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function toJson()
    {
        return json_encode($this->data);
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function isIndexExpired(string $index)
    {
        if (empty($index)) {
            return false;
        }

        $currentDateTime = new DateTime();
        $expiresDateTime = new DateTime($this->data[$index]['expires']);

        if ($expiresDateTime < $currentDateTime) {
            return false;
        }

        return true;
    }
}
