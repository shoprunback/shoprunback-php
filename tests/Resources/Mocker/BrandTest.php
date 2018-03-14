<?php

declare(strict_types=1);

namespace Tests\Resources\Mocker;

use \Tests\Resources\Mocker\BaseMockerTest;

use \Shoprunback\Resources\Brand;
use \Shoprunback\RestClient;

final class BrandTest extends BaseMockerTest
{
    use \Tests\Resources\BrandTrait;

    public function testCanUpdateOneMocked()
    {
        $brand = Brand::retrieve(1);
        $brand->name = self::randomString();
        $brand->save();

        $retrievedBrand = Brand::retrieve(1);

        $this->assertNotSame($retrievedBrand->name, $brand->name);
    }
}