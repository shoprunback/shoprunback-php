<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Shoprunback\RestClient;
use \Shoprunback\Error\NotFoundError;

use \Tests\BaseTest;

abstract class BaseResourceTest extends BaseTest
{
    abstract public static function getResourceClass();

    abstract public static function createDefault();

    abstract protected function checkIfHasNeededValues($object);

    public function testNewObjectIsNotPersisted()
    {
        $resourceClass = static::getResourceClass();
        $object = new $resourceClass();
        $this->assertFalse($object->isPersisted());
    }

    public function testNewObjectWithIdIsNotPersisted()
    {
        $resourceClass = static::getResourceClass();
        $object = new $resourceClass();
        $object->id = 1;
        $this->assertFalse($object->isPersisted());
    }

    public function testCanFetchOneMocked()
    {
        RestClient::getClient()->enableTesting();
        $this->checkIfHasNeededValues(static::getResourceClass()::retrieve(1));
    }

    public function testObjectFromMockerIsPersisted()
    {
        RestClient::getClient()->enableTesting();
        $this->assertTrue(static::getResourceClass()::retrieve(1)->isPersisted());
    }

    public function testCanFetchAllMocked()
    {
        RestClient::getClient()->enableTesting();

        $objects = static::getResourceClass()::all();
        $this->assertEquals(count($objects), 2);

        $object = $objects[0];
        $this->checkIfHasNeededValues($object);
    }

    public function testCanDeleteMocked()
    {
        RestClient::getClient()->enableTesting();

        $this->assertNull(static::getResourceClass()::delete(1));
    }

    public function testCanFetchAll()
    {
        RestClient::getClient()->disableTesting();
        $this->assertGreaterThan(0, count(static::getResourceClass()::all()));
    }

    /**
     * @expectedException \Shoprunback\Error\NotFoundError
     */
    public function testCanNotRetrieveUnknown()
    {
        static::getResourceClass()::retrieve(self::randomString());
    }

    public function testCanRetrieve()
    {
        RestClient::getClient()->disableTesting();

        $object = static::getResourceClass()::all()[0];

        $retrievedObject = static::getResourceClass()::retrieve($object->id);

        $this->assertSame($object->id, $retrievedObject->id);
    }
}