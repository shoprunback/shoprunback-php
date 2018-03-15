<?php

declare(strict_types=1);

namespace Tests\Resources\Api;

use \Tests\Resources\Api\BaseApiTest;

use \Shoprunback\Resources\Brand;
use \Shoprunback\Resources\Product;
use \Shoprunback\RestClient;

final class ProductTest extends BaseApiTest
{
    use \Tests\Resources\ProductTrait;

    public function testCanSaveNewProduct()
    {
        RestClient::getClient()->disableTesting();

        $product = self::createDefault();

        $label = $product->label;
        $reference = $product->reference;

        $product->save();

        $this->assertNotNull($product->id);
        $this->assertSame($product->label, $label);
        $this->assertSame($product->reference, $reference);
    }

    public function testCanUpdate()
    {
        RestClient::getClient()->disableTesting();

        $product = Product::all()[0];
        $productId = $product->id;
        $label = self::randomString();
        $product->label = $label;
        $product->save();

        $retrievedProduct = Product::retrieve($productId);

        $this->assertSame($retrievedProduct->label, $label);
    }

    public function testExistingProductExistingBrand()
    {
        RestClient::getClient()->disableTesting();

        $product = Product::all()[0];
        $product->brand = Brand::all()[1];

        $this->assertEquals($product->getDirtyKeys(), ['brand_id']);
        $this->assertEquals($product->brand->getDirtyKeys(), []);
    }

    public function testExistingProductExistingUpdatedBrand()
    {
        RestClient::getClient()->disableTesting();

        $product = Product::all()[0];
        $product->brand = Brand::all()[1];
        $product->brand->name = BrandTest::randomString();

        $this->assertEquals($product->getDirtyKeys(), ['brand_id', 'brand']);
        $this->assertEquals($product->brand->getDirtyKeys(), ['name']);
    }
}