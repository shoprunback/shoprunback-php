<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Shoprunback\Resources\Brand;
use \Shoprunback\RestClient;
use \Shoprunback\Error\NotFoundError;

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

    public function testBrandFromApiIsPersisted()
    {
        RestClient::getClient()->disableTesting();

        $reference = strval(rand());
        $brand = new Brand();
        $brand->name = $reference;
        $brand->reference = $reference;
        $this->assertFalse($brand->isPersisted());

        $brand->save();
        $this->assertTrue($brand->isPersisted());
    }

    public function testNewBrandIsPersisted()
    {
        $brand = new Brand();
        $this->assertFalse($brand->isPersisted());
    }

    public function testNewBrandWithIdIsPersisted()
    {
        $brand = new Brand();
        $brand->id = rand();
        $this->assertFalse($brand->isPersisted());
    }

    public function testBrandFromMockerIsPersisted()
    {
        RestClient::getClient()->enableTesting();

        $brand = Brand::retrieve(rand());
        $this->assertTrue($brand->isPersisted());
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

    public function testCanSaveNewBrand()
    {
        RestClient::getClient()->disableTesting();

        $name = "name".get_called_class().rand();
        $reference = "reference".get_called_class().rand();

        $brand = new Brand();
        $brand->name = $name;
        $brand->reference = $reference;
        $brand->save();

        $this->assertNotNull($brand->id);
        $this->assertSame($brand->name, $name);
        $this->assertSame($brand->reference, $reference);
    }

    public function testCanFetchAll()
    {
        RestClient::getClient()->disableTesting();

        $brands = Brand::all();
        $this->assertGreaterThan(0, count($brands));
    }

    public function testCanRetrieve()
    {
        RestClient::getClient()->disableTesting();

        $brand = Brand::all()[0];

        $retrievedBrand = Brand::retrieve($brand->id);

        $this->assertSame($brand->id, $retrievedBrand->id);
        $this->assertSame($brand->name, $retrievedBrand->name);
    }

    public function testCanUpdate()
    {
        RestClient::getClient()->disableTesting();

        $brand = Brand::all()[0];
        $brandId = $brand->id;
        $name = "name".get_called_class().rand();
        $brand->name = $name;
        $brand->save();

        $retrievedBrand = Brand::retrieve($brandId);

        $this->assertSame($retrievedBrand->name, $name);
    }

    /**
     * @expectedException \Shoprunback\Error\NotFoundError
     */
    public function testCanRetrieveUnknown()
    {
        Brand::retrieve("name".get_called_class().rand());
    }

    /**
     * @expectedException \Shoprunback\Error\NotFoundError
     */
    public function testCanDelete()
    {
        RestClient::getClient()->disableTesting();

        $name = "name".get_called_class().rand();
        $reference = "reference".get_called_class().rand();

        $brand = new Brand();
        $brand->name = $name;
        $brand->reference = $reference;
        $brand->save();

        $brand->remove();

        $retrievedBrand = Brand::retrieve($brand->id);
    }
}