<?php

declare(strict_types=1);

namespace Tests\Resources;

require_once dirname(dirname(__FILE__)) . '/Mocker.php';

use \Tests\BaseTest;
use \Tests\Mocker;

use \Shoprunback\Resources\ApiObject;
use \Shoprunback\Resources\Brand as Brand;
use \Shoprunback\Util\Converter;

abstract class ApiObjectTest extends BaseTest
{
    abstract public static function getClass();

    abstract public static function getClassId();

    abstract public static function getClassReference();

    abstract public static function getMockerJson();

    static public function getResourcePath()
    {
        return Converter::RESSOURCES_NAMESPACE . static::getClass();
    }

    public function testCanFetchWithId()
    {
        // Check GET with ID
        $result = Mocker::request(static::getResourcePath()::getApiUrlResource(), 'GET', ['id' => static::getClassId()]);
        $this->assertSame($result, static::getMockerJson());

        // Check GET with reference of class
        $result = Mocker::request(static::getResourcePath()::getApiUrlResource(), 'GET', ['reference' => static::getClassReference()]);
        $this->assertSame($result, static::getMockerJson());

        // Check if we can convert the JSON to an object
        $object = static::getResourcePath()::convertToSelf(json_decode($result));
        $this->assertInstanceOf(
            static::getResourcePath(),
            $object
        );
    }

    // public function testCanCreate()
    // {
    //     $item = static::getObjectSample();
    //     $this->assertNull($item->save());
    // }

    // public function testCanUpdate()
    // {
    //     $item = static::getResourcePath()::fetch(static::getObjectToFetch());
    //     $item = static::updateForTest($item);
    //     $this->assertNull($item->save());
    // }
}