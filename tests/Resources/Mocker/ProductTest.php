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

        $product = Product::retrieve(1);
        $product->label = self::randomString();
        $product->save();

        $retrievedProduct = Product::retrieve(1);

        $this->assertNotSame($retrievedProduct->label, $product->label);
    }

    public function testNewProductNewBrand()
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

    public function testNewProductExistingBrand()
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

    public function testExistingProductUpdatedBrand()
    {
        RestClient::getClient()->enableTesting();

        $product = Product::retrieve(1);
        $product->brand->name = BrandTest::randomString();

        $this->assertEquals($product->getDirtyKeys(), ['brand']);
        $this->assertEquals($product->brand->getDirtyKeys(), ['name']);
    }
}