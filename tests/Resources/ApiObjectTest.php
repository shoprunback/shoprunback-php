<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Shoprunback\Resources\ApiObject;
use \Shoprunback\Resources\Brand as Brand;
use \Shoprunback\Util\Converter;

abstract class ApiObjectTest extends BaseTest
{
    abstract public static function getClass();

    abstract public static function getObjectSample();

    abstract public static function getObjectToFetch();

    abstract public static function updateForTest($item);

    static public function getResourcePath()
    {
        return Converter::RESSOURCES_NAMESPACE . static::getClass();
    }

    public function testCanCreate()
    {
        $item = static::getObjectSample();
        $this->assertNull($item->save());
    }

    public function testCanFetch()
    {
        $this->assertInstanceOf(
            static::getResourcePath(),
            static::getResourcePath()::fetch(static::getObjectToFetch())
        );
    }

    public function testCanUpdate()
    {
        $item = static::getResourcePath()::fetch(static::getObjectToFetch());
        $item = static::updateForTest($item);
        $this->assertNull($item->save());
    }
}