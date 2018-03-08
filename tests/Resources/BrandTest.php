<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Tests\Resources\ShoprunbackObjectTest;
use \Shoprunback\Resources\Brand as Brand;
use \Shoprunback\RestClient;

final class BrandTest extends BaseTest
{
    const CLASS_NAME = 'Shoprunback\Resources\Brand';

    public function testCanFetchOneMocked()
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

    public function testCanFetchAllMocked()
    {
        RestClient::getClient()->enableTesting();

        $brands = Brand::all();
        $this->assertEquals(count($brands), 2);

        $brand = $brands[0];
        $this->assertNotNull($brand->id);
        $this->assertNotNull($brand->name);
        $this->assertNotNull($brand->reference);
    }
}