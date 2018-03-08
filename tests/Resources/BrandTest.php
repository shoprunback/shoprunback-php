<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Tests\Resources\ShoprunbackObjectTest;
use \Shoprunback\Resources\Brand as Brand;

final class BrandTest //extends ShoprunbackObjectTest
{
    const CLASS_NAME = 'Brand';

    public function testCanFetchMocked()
    {
        RestClient::getClient()->enableTesting();

        $brand = Brand::retrieve(rand());
        $this->assertInstanceOf(
            self::CLASS_NAME,
            $brand
        );

        $this->assertNotNull($brand->id);
        $this->assertNotNull($brand->name);
        $this->assertNotNull($brand->reference);
    }
}