<?php

namespace Tests\Resources;

use \Shoprunback\Resources\Brand;

trait BrandTrait
{
    public static function getResourceClass()
    {
        return 'Shoprunback\Resources\Brand';
    }

    protected static function createDefault()
    {
        $name = self::randomString();
        $reference = self::randomString();

        $brand = new Brand();
        $brand->name = $name;
        $brand->reference = $reference;

        return $brand;
    }

    protected function checkIfHasNeededValues($brand)
    {
        $this->assertInstanceOf(
            self::getResourceClass(),
            $brand
        );

        $this->assertNotNull($brand->id);
        $this->assertNotNull($brand->name);
        $this->assertNotNull($brand->reference);
    }
}