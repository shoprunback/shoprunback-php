<?php

declare(strict_types=1);

namespace Tests\Resources\Api;

use Tests\Resources\BaseResourceTest;

use \Shoprunback\RestClient;

abstract class BaseApiTest extends BaseResourceTest
{
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

    public function testObjectFromApiIsPersisted()
    {
        RestClient::getClient()->disableTesting();

        $object = static::createDefault();
        $this->assertFalse($object->isPersisted());

        $object->save();
        $this->assertTrue($object->isPersisted());
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

    /**
     * @expectedException \Shoprunback\Error\NotFoundError
     */
    public function testCanDelete()
    {
        RestClient::getClient()->disableTesting();

        $object = static::createDefault();
        $object->save();

        $object->remove();

        static::getResourceClass()::retrieve($object->id);
    }
}