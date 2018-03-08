<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Shoprunback\Resources\ApiObject;
use \Shoprunback\Resources\Brand as Brand;
use \Shoprunback\Util\Inflector;
use \Shoprunback\RestClient;

abstract class ApiObjectTest extends BaseTest
{
    abstract public static function getClass();

    static public function getResourcePath()
    {
        return Inflector::RESSOURCES_NAMESPACE . static::getClass();
    }

    public function testCanFetchWithId()
    {
        RestClient::getClient()->enableTesting();

        $object = static::getResourcePath()::retrieve('thenamedoesnotmatter');
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