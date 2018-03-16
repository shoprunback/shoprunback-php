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

    public function testGetChangedBrandBody()
    {
        RestClient::getClient()->enableTesting();

        $brand = Brand::retrieve(1);

        $brand->name = static::randomString();
        $resourceBody = $brand->getResourceBody(false);
        $this->assertTrue(property_exists($resourceBody, 'name'));
        $this->assertEquals(count(get_object_vars($resourceBody)), 1);
    }

    public function testGetNewBrandBody()
    {
        RestClient::getClient()->enableTesting();

        $brand = new Brand();

        $brand->name = static::randomString();
        $resourceBody = $brand->getResourceBody(false);
        $this->assertTrue(property_exists($resourceBody, 'name'));
        $this->assertEquals(count(get_object_vars($resourceBody)), 1);

        $brand->reference = static::randomString();
        $resourceBody = $brand->getResourceBody(false);
        $this->assertTrue(property_exists($resourceBody, 'reference'));
        $this->assertEquals(count(get_object_vars($resourceBody)), 2);
    }
}