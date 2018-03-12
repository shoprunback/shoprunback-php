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

    public function testNewBrandIsNotPersisted()
    {
        $brand = new Brand();
        $this->assertFalse($brand->isPersisted());
    }

    public function testNewBrandWithIdIsNotPersisted()
    {
        $brand = new Brand();
        $brand->id = 1;
        $this->assertFalse($brand->isPersisted());
    }

    public function testBrandFromMockerIsPersisted()
    {
        RestClient::getClient()->enableTesting();

        $brand = Brand::retrieve(1);
        $this->assertTrue($brand->isPersisted());
    }

    public function testCanFetchOneMocked()
    {
        RestClient::getClient()->enableTesting();

        $brand = Brand::retrieve(1);

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

    public function testCanSaveMocked()
    {
        RestClient::getClient()->enableTesting();

        $brand = new Brand();
        $brand->name = self::randomString();
        $brand->reference = self::randomString();
        $brand->save();

        $this->assertNotNull($brand->id);
    }

    public function testCanUpdateOneMocked()
    {
        $brand = Brand::retrieve(1);
        $brand->name = self::randomString();
        $brand->save();

        $retrievedBrand = Brand::retrieve(1);

        $this->assertNotSame($retrievedBrand->name, $brand->name);
    }

    public function testCanDeleteMocked()
    {
        RestClient::getClient()->enableTesting();

        $this->assertNull(Brand::delete(1));
    }

    public function testCanSaveNewBrand()
    {
        RestClient::getClient()->disableTesting();

        $name = self::randomString();
        $reference = self::randomString();

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

    /**
     * @expectedException \Shoprunback\Error\NotFoundError
     */
    public function testCanRetrieveUnknown()
    {
        Brand::retrieve(self::randomString());
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
        $name = self::randomString();
        $brand->name = $name;
        $brand->save();

        $retrievedBrand = Brand::retrieve($brandId);

        $this->assertSame($retrievedBrand->name, $name);
    }

    /**
     * @expectedException \Shoprunback\Error\NotFoundError
     */
    public function testCanDelete()
    {
        RestClient::getClient()->disableTesting();

        $name = self::randomString();
        $reference = self::randomString();

        $brand = new Brand();
        $brand->name = $name;
        $brand->reference = $reference;
        $brand->save();

        $brand->remove();

        $retrievedBrand = Brand::retrieve($brand->id);
    }
}