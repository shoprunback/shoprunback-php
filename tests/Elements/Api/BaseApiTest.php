<?php

namespace Tests\Elements\Api;

use Tests\Elements\BaseElementTest;

use \Shoprunback\RestClient;

abstract class BaseApiTest extends BaseElementTest
{
    public function testNewObjectIsNotPersisted()
    {
        $elementClass = static::getElementClass();
        $object = new $elementClass();
        $this->assertFalse($object->isPersisted());
    }

    public function testNewObjectWithIdIsNotPersisted()
    {
        $elementClass = static::getElementClass();
        $object = new $elementClass();
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
        $this->assertGreaterThan(0, static::getElementClass()::all()->count);
    }

    public function testCanIterate()
    {
        RestClient::getClient()->disableTesting();

        $elements = static::getElementClass()::all();
        $this->assertNotNull($elements[0]->id);
        $this->assertNotNull($elements[$elements->count - 1]);

        if ($elements->count > $elements->per_page && !is_null($elements->next_page)) {
            $this->assertNotNull($elements[$elements->per_page + 1]->id);
            $this->assertNotEquals(count($elements), $elements->count);
            $this->assertEquals($elements->per_page, count($elements));
        }

        $this->assertTrue(is_array($elements) || $elements instanceof \Traversable);
    }

    /**
     * @expectedException \Shoprunback\Error\ElementNumberDoesntExists
     */
    public function testExceptionOnWrongIteration()
    {
        RestClient::getClient()->disableTesting();

        $elements = static::getElementClass()::all();
        $elements[$elements->count + 1];
    }

    /**
     * @expectedException \Shoprunback\Error\NotFoundError
     */
    public function testCanNotRetrieveUnknown()
    {
        static::getElementClass()::retrieve(self::randomString());
    }

    public function testCanRetrieve()
    {
        RestClient::getClient()->disableTesting();

        $object = static::getElementClass()::all()[0];

        $retrievedObject = static::getElementClass()::retrieve($object->id);

        $this->assertSame($object->id, $retrievedObject->id);
    }

    /**
     * @expectedException \Shoprunback\Error\NotFoundError
     */
    public function testCanDelete()
    {
        RestClient::getClient()->disableTesting();

        if (static::getElementClass()::canDelete()) {
            $object = static::createDefault();
            $object->save();

            $object->remove();

            static::getElementClass()::retrieve($object->id);
        } else {
            throw new \Shoprunback\Error\NotFoundError('Test worked');
        }
    }
}