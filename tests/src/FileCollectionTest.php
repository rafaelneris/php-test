<?php

namespace Live\Collection;

use PHPUnit\Framework\TestCase;

/**
 * Class FileCollectionTest
 * @package Live\Collection
 * @author Rafael Neris <rafaelnerisdj@gmail.com>
 */
class FileCollectionTest extends TestCase
{

    /** @var \Live\Collection\FileCollection */
    private $collection;


    public function setUp(): void
    {
        $this->collection = new FileCollection('unitTest.txt');
    }

    /**
     * @test
     * @covers \Live\Collection\FileCollection::__construct
     */
    public function objectCanBeConstructed()
    {
        $collection = new FileCollection('testeFile');
        $this->assertIsObject($collection);
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @throws \Exception
     */
    public function writeFileWithOutExpiresValue()
    {
        $collection = clone $this->collection;
        $collection->set('testIndex', 'valueTest');
        $collection->set('testIndex2', 'valueTest2');

        $this->assertTrue($collection->write());
    }


    /**
     * @test
     * @depends objectCanBeConstructed
     * @throws \Exception
     */
    public function writeFileWithtExpiresValue()
    {
        $collection = clone $this->collection;
        $collection->set('testIndex', 'valueTest', 3);
        $collection->set('testIndex', 'valueTest', 7);

        $this->assertTrue($collection->write());
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @doesNotPerformAssertions
     */
    public function dataCanBeAdded()
    {
        $collection = clone $this->collection;
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);
        $collection->set('index4', 6.5);
        $collection->set('index5', ['data']);
        $collection->set('index5', ['data'], 60);
        $collection->set('index5', ['data'], 1);
    }

    /**
     * @test
     * @depends testWriteFileWithOutExpiresValue
     */
    public function deleteFile()
    {
        $collection = clone $this->collection;
        $this->assertTrue($collection->deleteFile());
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @throws \Exception
     */
    public function testWriteFileWithOutExpiresValue()
    {
        $collection = clone $this->collection;
        $collection->set('testIndex', 'valueTest');

        $this->assertTrue($collection->write());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     * @throws \Exception
     */
    public function dataCanBeRetrieved()
    {
        $collection = clone $this->collection;
        $collection->set('index1', 'value', 30);
        $index = $collection->get('index1');
        $this->assertArrayHasKey('value', $index);
        $this->assertArrayHasKey('expires', $index);
        $this->assertEquals('value', $index['value']);
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @throws \Exception
     */
    public function expiredIndex()
    {
        $collection = clone $this->collection;
        $collection->set('index1', '1234', 1);
        $collection->set('index2', 3442, 2);
        $collection->set('index3', false, 2);
        sleep(1);

        $failIndex = $collection->get('index1');

        $this->assertIsArray($failIndex);
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @throws \Exception
     */
    public function unvaiableIndexShouldReturnDefaultValue()
    {
        $collection = clone $this->collection;

        $this->assertNull($collection->get('index1'));
        $this->assertEquals('defaultValue', $collection->get('index1', 'defaultValue'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     * @throws \Exception
     */
    public function expiringIndex()
    {

        $collection = clone $this->collection;
        $collection->set('index1', '1234', 1);
        $collection->set('index2', 2139, 30);
        $collection->set('index3', null, 0.1);
        sleep(1);
        $this->assertFalse($collection->isIndexExpired('index1'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     * @throws \Exception
     */
    public function dontExpiredIndex()
    {

        $collection = clone $this->collection;
        $collection->set('index2', 2139, 30);

        $this->assertTrue($collection->isIndexExpired('index2'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @throws \Exception
     */
    public function inexistentIndexShouldReturnDefaultValue()
    {
        $collection = clone $this->collection;

        $this->assertNull($collection->get('index1'));

        $this->assertEquals('defaultValue', $collection->get('index1', 'defaultValue'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     */
    public function newCollectionShouldNotContainItems()
    {
        $collection = clone $this->collection;
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function collectionWithItemsShouldReturnValidCount()
    {
        $collection = clone $this->collection;
        $collection->set('index1', 'value');
        $collection->set('index2', 5);
        $collection->set('index3', true);

        $this->assertEquals(3, $collection->count());
    }

    /**
     * @test
     * @depends collectionWithItemsShouldReturnValidCount
     */
    public function collectionCanBeCleaned()
    {
        $collection = clone $this->collection;
        $collection->set('index', 'value');
        $this->assertEquals(1, $collection->count());

        $collection->clean();
        $this->assertEquals(0, $collection->count());
    }

    /**
     * @test
     * @depends dataCanBeAdded
     */
    public function addedItemShouldExistInCollection()
    {
        $collection = $this->collection;
        $collection->set('index', 'value');

        $this->assertTrue($collection->has('index'));
    }

    /**
     * @test
     * @depends dataCanBeAdded
     * @throws \Exception
     */
    public function getWithoutIndexValue()
    {
        $collection = new FileCollection('testeFile');
        $collection->set('index', 'value');

        $this->assertNull($collection->get('index1'));
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @throws \Exception
     */
    public function collectionToJson()
    {
        $collection = new FileCollection('testeFile');
        $collection->set('index', 'value');
        $this->assertIsString($collection->toJson());
    }

    /**
     * @test
     * @depends objectCanBeConstructed
     * @throws \Exception
     */
    public function verifyFileExists()
    {
        $collection = new FileCollection('testeFile');
        $collection->set('index', 'value');

        $this->assertTrue($collection->write());

        $this->assertFileExists('testeFile');
    }

    /**
     * @test
     * @throws \Exception
     */
    public function emptyIndexInVerificationIndexExpired()
    {
        $collection = clone $this->collection;
        $this->assertFalse($collection->isIndexExpired(null));
    }
}
