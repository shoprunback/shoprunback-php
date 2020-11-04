<?php

namespace Tests\Elements\Mocker;

use \Tests\Elements\Mocker\BaseMockerTest;

use \Shoprunback\Elements\SparePart;
use \Shoprunback\RestClient;

final class SparePartTest extends BaseMockerTest
{
    use \Tests\Elements\SparePartTrait;

    public function testCanUpdateOneMocked()
    {
        static::enableTesting();

        $sparepart = SparePart::retrieve(1);
        $sparepart->name = self::randomString();
        $sparepart->save();

        $retrievedSparepart = SparePart::retrieve(1);

        $this->assertNotSame($retrievedSparepart->name, $sparepart->name);
    }

    public function testGetChangedSparePartBody()
    {
        static::enableTesting();

        $sparePart = SparePart::retrieve(1);

        $sparePart->name = static::randomString();
        $elementBody = $sparePart->getElementBody(false);
        $this->assertTrue(property_exists($elementBody, 'name'));
        $this->assertEquals(count(get_object_vars($elementBody)), 1);
    }

    public function testGetNewSparePartBody()
    {
        static::enableTesting();

        $sparePart = new SparePart();

        $sparePart->name = static::randomString();
        $elementBody = $sparePart->getElementBody(false);
        $this->assertTrue(property_exists($elementBody, 'name'));
        $this->assertEquals(count(get_object_vars($elementBody)), 1);

        $sparePart->reference = static::randomString();
        $elementBody = $sparePart->getElementBody(false);
        $this->assertTrue(property_exists($elementBody, 'reference'));
        $this->assertEquals(count(get_object_vars($elementBody)), 2);
    }
}
