<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Shoprunback\Resources\Brand;
use \Shoprunback\RestClient;

final class BrandTest extends BaseTest
{
    const CLASS_NAME = 'Shoprunback\Resources\Brand';

    private function checkIfHasNeededValues($brand)
    {
        $this->assertInstanceOf(
            self::CLASS_NAME,
            $brand
        );

        $this->assertNotNull($brand->id);
        $this->assertNotNull($brand->name);
        $this->assertNotNull($brand->reference);
    }

    public function testCanFetchOneMocked()
    {
        RestClient::getClient()->enableTesting();

        $brand = Brand::retrieve(rand());

        $this->checkIfHasNeededValues($brand);
    }

    public function testCanFetchAllMocked()
    {
        RestClient::getClient()->enableTesting();

        $brands = Brand::all();
        $this->assertEquals(count($brands), 2);

        $brand = $brands[0];
        $this->checkIfHasNeededValues($brand);
    }

    public function testCanUpdateOneMocked()
    {
        RestClient::getClient()->enableTesting();

        $brand = Brand::retrieve(rand());

        // Check if _origValues has the same values as the base object since the object hasn't been changed
        $brandWithoutOrigValues = clone $brand;
        unset($brandWithoutOrigValues->_origValues);
        $this->assertSame($brand->_origValues->id, $brandWithoutOrigValues->id);
        $this->assertSame($brand->_origValues->name, $brandWithoutOrigValues->name);
        $this->assertSame($brand->_origValues->reference, $brandWithoutOrigValues->reference);

        $name = $brand->name . 'A';
        $brand->name = $name;
        $this->assertNotSame($brand->name, $brandWithoutOrigValues->name);

        $brand = Brand::update($brand);
        $this->assertSame($brand->name, $name);

        // Check if _origValues has correctly been changed
        $brandWithoutOrigValues = clone $brand;
        unset($brandWithoutOrigValues->_origValues);
        $this->assertSame($brand->_origValues->id, $brandWithoutOrigValues->id);
        $this->assertSame($brand->_origValues->name, $brandWithoutOrigValues->name);
        $this->assertSame($brand->_origValues->reference, $brandWithoutOrigValues->reference);
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

        // Test Delete
        Brand::delete($updatedBrand->id);
        // Must throw an Exception
        Brand::retrieve($updatedBrand->id);
    }
}