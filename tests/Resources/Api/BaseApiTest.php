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
        $this->assertGreaterThan(0, static::getResourceClass()::all()->count);
    }

    public function testCanIterate()
    {
        RestClient::getClient()->disableTesting();

        $resources = static::getResourceClass()::all();
        $this->assertNotNull($resources[0]->id);
        $this->assertNotNull($resources[$resources->count - 1]);

        if ($resources->count > $resources->per_page) {
            $this->assertNotNull($resources[$resources->per_page + 1]->id);
        }
    }


    /**
     * @expectedException \Shoprunback\Error\ResourceNumberDoesntExists
     */
    public function testExceptionOnWrongIteration()
    {
        RestClient::getClient()->disableTesting();

        $resources = static::getResourceClass()::all();
        $resources[$resources->count + 1];
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