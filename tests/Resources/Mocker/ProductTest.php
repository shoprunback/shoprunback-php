<?php

declare(strict_types=1);

namespace Tests\Resources\Mocker;

use \Tests\Resources\Mocker\BaseMockerTest;
use \Tests\Resources\Mocker\BrandTest;

use \Shoprunback\Resources\Brand;
use \Shoprunback\Resources\Product;
use \Shoprunback\RestClient;

final class ProductTest extends BaseMockerTest
{
    use \Tests\Resources\ProductTrait;

    public function testCanUpdateOneMocked()
    {
        RestClient::getClient()->enableTesting();

        $product = self::getResourceClass()::retrieve(1);
        $product->label = self::randomString();
        $product->save();

        $retrievedProduct = self::getResourceClass()::retrieve(1);

        $this->assertNotSame($retrievedProduct->label, $product->label);
    }

    public function testGetNewProductNewBrandDirtyKeys()
    {
        RestClient::getClient()->enableTesting();

        $product = new Product();
        $product->label = self::randomString();
        $product->reference = self::randomString();
        $product->weight_grams = 1000;

        $brand = new Brand();
        $brand->name = self::randomString();
        $brand->reference = self::randomString();

        $product->brand = $brand;

        $this->assertEquals($product->getDirtyKeys(), ['label', 'reference', 'weight_grams', 'brand', 'brand_id'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertEquals($product->brand->getDirtyKeys(), ['name', 'reference'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
    }

    public function testGetNewProductExistingBrandDirtyKeys()
    {
        RestClient::getClient()->enableTesting();

        $product = new Product();
        $product->label = self::randomString();
        $product->reference = self::randomString();
        $product->weight_grams = 1000;
        $product->brand = Brand::retrieve(1);

        $this->assertEquals($product->getDirtyKeys(), ['label', 'reference', 'weight_grams', 'brand_id'], "\$canonicalize = true", $delta = 0.0, $maxDepth = 10, $canonicalize = true);
        $this->assertEquals($product->brand->getDirtyKeys(), []);
    }

    public function testGetExistingProductUpdatedBrandDirtyKeys()
    {
        RestClient::getClient()->enableTesting();

        $product = self::getResourceClass()::retrieve(1);
        $product->brand->name = BrandTest::randomString();

        $this->assertEquals($product->getDirtyKeys(), ['brand']);
        $this->assertEquals($product->brand->getDirtyKeys(), ['name']);
    }

    public function testGetChangedProductBody()
    {
        RestClient::getClient()->enableTesting();

        $product = self::getResourceClass()::retrieve(1);

        $product->label = self::randomString();
        $resourceBody = $product->getResourceBody(false);
        $this->assertTrue(property_exists($resourceBody, 'label'));
        $this->assertEquals(count(get_object_vars($resourceBody)), 1);
    }

    public function testGetNewProductBody()
    {
        RestClient::getClient()->enableTesting();

        $product = new Product();

        $product->label = self::randomString();
        $resourceBody = $product->getResourceBody(false);
        $this->assertTrue(property_exists($resourceBody, 'label'));
        $this->assertEquals(count(get_object_vars($resourceBody)), 1);

        $product->reference = self::randomString();
        $resourceBody = $product->getResourceBody(false);
        $this->assertTrue(property_exists($resourceBody, 'reference'));
        $this->assertEquals(count(get_object_vars($resourceBody)), 2);
    }

    public function testGetNewProductNewBrandBody()
    {
        RestClient::getClient()->enableTesting();

        $brand = new Brand();
        $brand->name = BrandTest::randomString();
        $brand->reference = BrandTest::randomString();

        $product = new Product();
        $product->brand = $brand;

        $resourceBody = $product->getResourceBody(false);
        $this->assertEquals(count(get_object_vars($resourceBody)), 1);
        $this->assertTrue(property_exists($resourceBody, 'brand'));
    }

    public function testGetNewProductRetrievedBrandBody()
    {
        RestClient::getClient()->enableTesting();

        $brand = Brand::retrieve(1);

        $product = new Product();
        $product->brand = $brand;

        $resourceBody = $product->getResourceBody(false);
        $this->assertEquals(count(get_object_vars($resourceBody)), 1);
        $this->assertTrue(property_exists($resourceBody, 'brand_id'));
    }

    public function testGetRetrievedProductNewBrandBody()
    {
        RestClient::getClient()->enableTesting();

        $brand = new Brand();
        $brand->name = BrandTest::randomString();
        $brand->reference = BrandTest::randomString();

        $product = Product::retrieve(1);
        $product->brand = $brand;

        $resourceBody = $product->getResourceBody(false);
        $this->assertEquals(count(get_object_vars($resourceBody)), 1);
        $this->assertTrue(property_exists($resourceBody, 'brand'));
    }
}