<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\Resources\BaseResourceTest;

use \Shoprunback\Resources\Brand;
use \Shoprunback\RestClient;
use \Shoprunback\Error\NotFoundError;

final class BrandTest extends BaseResourceTest
{
    public static function getResourceClass()
    {
        return 'Shoprunback\Resources\Brand';
    }

    public static function createDefault()
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

    public function testBrandFromApiIsPersisted()
    {
        RestClient::getClient()->disableTesting();

        $brand = self::createDefault();
        $this->assertFalse($brand->isPersisted());

        $brand->save();
        $this->assertTrue($brand->isPersisted());
    }

    public function testCanSaveMocked()
    {
        RestClient::getClient()->enableTesting();

        $brand = self::createDefault();
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

    public function testCanSaveNewBrand()
    {
        RestClient::getClient()->disableTesting();

        $brand = self::createDefault();

        $name = $brand->name;
        $reference = $brand->reference;

        $brand->save();

        $this->assertNotNull($brand->id);
        $this->assertSame($brand->name, $name);
        $this->assertSame($brand->reference, $reference);
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

        $brand = self::createDefault();
        $brand->save();

        $brand->remove();

        Brand::retrieve($brand->id);
    }
}