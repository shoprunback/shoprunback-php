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

    public function testGetExistingProductExistingBrandDirtyKeys()
    {
        RestClient::getClient()->disableTesting();

        $product = Product::all()[0];
        $product->brand = Brand::all()[1];

        $this->assertEquals($product->getDirtyKeys(), ['brand_id']);
        $this->assertEquals($product->brand->getDirtyKeys(), []);
    }

    public function testGetExistingProductNewBrandDirtyKeys()
    {
        RestClient::getClient()->disableTesting();

        $product = Product::all()[0];
        $brand = new Brand();
        $brand->name = BrandTest::randomString();
        $brand->reference = BrandTest::randomString();

        $product->brand = $brand;

        $this->assertEquals($product->getDirtyKeys(), ['brand']);
        $this->assertEquals($product->brand->getDirtyKeys(), ['name', 'reference'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
    }

    public function testGetExistingProductExistingUpdatedBrandDirtyKeys()
    {
        RestClient::getClient()->disableTesting();

        $product = Product::all()[0];
        $product->brand = Brand::all()[1];
        $product->brand->name = BrandTest::randomString();

        $this->assertEquals($product->getDirtyKeys(), ['brand_id', 'brand']);
        $this->assertEquals($product->brand->getDirtyKeys(), ['name']);
    }

    public function testGetRetrievedProductRetrievedBrandBody()
    {
        RestClient::getClient()->enableTesting();

        $product = Product::all()[0];
        $product->brand = Brand::all()[1];

        $resourceBody = $product->getResourceBody(false);
        $this->assertEquals(count(get_object_vars($resourceBody)), 1);
        $this->assertTrue(property_exists($resourceBody, 'brand_id'));
    }

    public function testGetRetrievedProductChangeRetrievedBrandBody()
    {
        RestClient::getClient()->enableTesting();

        $product = Product::all()[0];
        $product->brand = Brand::all()[1];
        $product->brand->name = BrandTest::randomString();

        $resourceBody = $product->getResourceBody(false);
        $this->assertEquals(count(get_object_vars($resourceBody)), 2);
        $this->assertTrue(property_exists($resourceBody, 'brand'));
        $this->assertTrue(property_exists($resourceBody, 'brand_id'));

        $resourceBody = $product->brand->getResourceBody(false);
        $this->assertEquals(count(get_object_vars($resourceBody)), 1);
        $this->assertTrue(property_exists($resourceBody, 'name'));
    }
}