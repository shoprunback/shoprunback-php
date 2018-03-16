<?php

declare(strict_types=1);

namespace Tests\Resources\Api;

use \Tests\Resources\Api\BaseApiTest;

use \Shoprunback\Resources\Brand;
use \Shoprunback\RestClient;
use \Shoprunback\Error\NotFoundError;

final class BrandTest extends BaseApiTest
{
    use \Tests\Resources\BrandTrait;

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

        $brand = Brand::all()[0][0];
        $brandId = $brand->id;
        $name = self::randomString();
        $brand->name = $name;
        $brand->save();

        $retrievedBrand = Brand::retrieve($brandId);

        $this->assertSame($retrievedBrand->name, $name);
    }
}