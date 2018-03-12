<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Shoprunback\RestClient;
use \Shoprunback\Resources\Brand;
use \Shoprunback\Util\Container;

final class ContainerTest extends BaseTest
{
    public function testAddToMixed()
    {
        $array = [];
        $key = 'key';
        $value = 'value';
        Container::addValueToContainer($array, $key, $value);
        $this->assertSame($array[$key], $value);

        $object = new \stdClass();
        Container::addValueToContainer($object, $key, $value);
        $this->assertSame($object->$key, $value);

        $this->assertSame($array[$key], $object->$key);
    }

    public function testIsContainer()
    {
        $this->assertTrue(Container::isContainer([]));
        $this->assertTrue(Container::isContainer(new \stdClass()));
        $this->assertFalse(Container::isContainer('a'));
        $this->assertFalse(Container::isContainer(1));
    }
}