<?php

namespace Tests\Elements;

use \Shoprunback\Elements\SparePart;

trait SparePartTrait
{
    public static function getElementClass()
    {
        return 'Shoprunback\Elements\SparePart';
    }

    public static function createDefault()
    {
        $name = self::randomString();
        $reference = self::randomString();

        $sparePart = new SparePart();
        $sparePart->name = $name;
        $sparePart->reference = $reference;

        return $sparePart;
    }

    protected function checkIfHasNeededValues($sparePart)
    {
        $this->assertInstanceOf(
            self::getElementClass(),
            $sparePart
        );

        $this->assertNotNull($sparePart->id);
        $this->assertNotNull($sparePart->name);
        $this->assertNotNull($sparePart->reference);
    }

    public function testCanUpdate()
    {
        $this->assertTrue(static::getElementClass()::canUpdate());
    }

    public function testCanDelete()
    {
        $this->assertTrue(static::getElementClass()::canDelete());
    }

    public function testGetBaseEndpoint()
    {
        $this->assertSame(static::getElementClass()::getBaseEndpoint(), 'spare_parts');
    }
}
