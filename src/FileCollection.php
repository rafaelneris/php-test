<?php


namespace Live\Collection;

use DomainException;

/**
 * Class FileCollection
 * @package Live\Collection
 * @author Rafael Neris <rafaelnerisdj@gmail.com>
 */
class FileCollection extends CollectionAbstract implements CollectionInterface
{

    /** @var string */
    const FILE_MODE = 'a+';

    /** @var string */
    private $filePath;

    /** @var bool|resource */
    private $fileResource;

    /**
     * FileCollection constructor.
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->fileResource = fopen($this->filePath, self::FILE_MODE);
        parent::__construct();
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function write()
    {
        if (!fwrite($this->fileResource, $this->toJson())) {
            throw new DomainException("Could not write file.");
        }

        return true;
    }

    /**
     * @return bool
     */
    public function deleteFile()
    {
        if (!file_exists($this->filePath)) {
            return false;
        }

        @unlink($this->filePath);

        return true;
    }
}
