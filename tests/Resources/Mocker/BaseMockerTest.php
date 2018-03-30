<?php

declare(strict_types=1);

namespace Tests\Resources\Mocker;

use Tests\Resources\BaseResourceTest;

use \Shoprunback\RestClient;

abstract class BaseMockerTest extends BaseResourceTest
{
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
        $this->assertEquals($objects->count, 2);

        $object = $objects[0];
        $this->checkIfHasNeededValues($object);
    }

    public function testCanSaveMocked()
    {
        RestClient::getClient()->enableTesting();

        $object = static::createDefault();
        $object->save();

        $this->assertNotNull($object->id);
    }

    public function testCanDeleteMocked()
    {
        RestClient::getClient()->enableTesting();

        $this->assertNull(static::getResourceClass()::delete(1));
    }

    public function testGetUnchangedResourceBody()
    {
        RestClient::getClient()->enableTesting();

        $resource = static::getResourceClass()::retrieve(1);
        $this->assertEquals(count(get_object_vars($resource->getResourceBody(false))), 0);
    }
}