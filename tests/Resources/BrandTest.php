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

    public function testCanUpdateOneMocked()
    {
        RestClient::getClient()->enableTesting();

        $brand = Brand::retrieve(rand());

        $name = $brand->name . 'A';
        $brand->name = $name;
        $brand = Brand::update($brand);
        $this->assertSame($brand->name, $name);
    }

    public function testCanCreateMocked()
    {
        RestClient::getClient()->enableTesting();

        $brand = new Brand();
        $brand->name = 'final fantasy';
        $brand->reference = 'final-fantasy';
        $createdBrand = Brand::create($brand);

        $this->assertNotNull($createdBrand->id);
        $this->assertSame($createdBrand->name, 'final fantasy');
        $this->assertSame($createdBrand->reference, 'final-fantasy');
    }

    public function testCanDeleteMocked()
    {
        RestClient::getClient()->enableTesting();

        $this->assertNull(Brand::delete(rand()));
    }

    /**
     * @expectedException \Exception
     */
    public function testAllApi()
    {
        RestClient::getClient()->disableTesting();

        $name = rand();
        $reference = $name + 1;

        // Test Create
        $brand = new Brand();
        $brand->name = $name;
        $brand->reference = $reference;
        $createdBrand = Brand::create($brand);

        $this->assertNotNull($createdBrand->id);
        $this->assertSame($createdBrand->name, $name);
        $this->assertSame($createdBrand->reference, $reference);

        // Test Retrieve
        $fetchedBrand = Brand::retrieve($createdBrand->id);
        $this->assertSame($createdBrand, $fetchedBrand);

        // Test Update
        $fetchedBrand->name = $name + 2;
        $updatedBrand = Brand::update($fetchedBrand);
        $this->assertNotSame($fetchedBrand, $updatedBrand);

        // Test Delete, must throw an Exception
        Brand::delete($updatedBrand->id);
        Brand::retrieve($updatedBrand->id);
    }
}