<?php

declare(strict_types=1);

namespace Tests\Resources;

require_once('BrandTest.php');

use \Tests\Resources\BaseResourceTest;
use \Tests\Resources\BrandTest;

use \Shoprunback\Resources\Product;
use \Shoprunback\Resources\Brand;
use \Shoprunback\RestClient;
use \Shoprunback\Error\NotFoundError;

final class ProductTest extends BaseResourceTest
{
    public static function getResourceClass()
    {
        return 'Shoprunback\Resources\Product';
    }

    public static function createDefault()
    {
        $label = self::randomString();
        $reference = self::randomString();

        $product = new Product();
        $product->label = $label;
        $product->reference = $reference;
        $product->weight_grams = 1000;

        if (RestClient::getClient()->isTesting()) {
            $product->brand = Brand::retrieve(1);
        } else {
            $product->brand = Brand::all()[0];
        }

        return $product;
    }

    protected function checkIfHasNeededValues($product)
    {
        $this->assertInstanceOf(
            self::getResourceClass(),
            $product
        );

        $this->assertNotNull($product->id);
        $this->assertNotNull($product->label);
        $this->assertNotNull($product->reference);
        $this->assertNotNull($product->ean);
        $this->assertNotNull($product->weight_grams);
        $this->assertNotNull($product->width_mm);
        $this->assertNotNull($product->length_mm);
        $this->assertNotNull($product->height_mm);
        $this->assertNotNull($product->width_mm);
        $this->assertNotNull($product->picture_file_base64);
        $this->assertNotNull($product->picture_file_url);
        $this->assertNotNull($product->created_at);
        $this->assertNotNull($product->updated_at);
        $this->assertNotNull($product->picture_url);

        $this->assertSame($product->brand_id, $product->brand->id);
        $this->assertInstanceOf(
            BrandTest::getResourceClass(),
            $product->brand
        );
    }

    public function testCanFetchOneMocked()
    {
        RestClient::getClient()->enableTesting();
        $this->checkIfHasNeededValues(Product::retrieve(1));
    }

    public function testCanFetchAllMocked()
    {
        RestClient::getClient()->enableTesting();

        $products = Product::all();
        $this->assertEquals(count($products), 2);

        $this->checkIfHasNeededValues($products[0]);
    }

    public function testBrandFromApiIsPersisted()
    {
        RestClient::getClient()->disableTesting();

        $reference = strval(rand());
        $product = self::createDefault();
        $this->assertFalse($product->isPersisted());

        $product->save();
        $this->assertTrue($product->isPersisted());
    }

    public function testCanSaveMocked()
    {
        RestClient::getClient()->enableTesting();

        $product = self::createDefault();
        $product->save();

        $this->assertNotNull($product->id);
    }

    public function testCanUpdateOneMocked()
    {
        $product = Product::retrieve(1);
        $product->label = self::randomString();
        $product->save();

        $retrievedProduct = Product::retrieve(1);

        $this->assertNotSame($retrievedProduct->label, $product->label);
    }

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

    /**
     * @expectedException \Shoprunback\Error\NotFoundError
     */
    public function testCanDelete()
    {
        RestClient::getClient()->disableTesting();

        $product = self::createDefault();
        $product->save();

        $product->remove();

        Product::retrieve($product->id);
    }
}