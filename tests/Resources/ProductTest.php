<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;
use \Tests\Resources\BrandTest;

use \Shoprunback\Resources\Product;
use \Shoprunback\Resources\Brand;
use \Shoprunback\RestClient;

final class ProductTest extends BaseTest
{
    const CLASS_NAME = 'Shoprunback\Resources\Product';

    private function checkIfHasNeededValues($product)
    {
        $this->assertInstanceOf(
            self::CLASS_NAME,
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
            BrandTest::CLASS_NAME,
            $product->brand
        );
    }

    public function testCanFetchOneMocked()
    {
        RestClient::getClient()->enableTesting();

        $product = Product::retrieve(rand());

        $this->checkIfHasNeededValues($product);
    }

    public function testCanFetchAllMocked()
    {
        RestClient::getClient()->enableTesting();

        $products = Product::all();
        $this->assertEquals(count($products), 2);

        $this->checkIfHasNeededValues($products[0]);
    }

    public function testCanUpdateOneMocked()
    {
        RestClient::getClient()->enableTesting();

        $product = Product::retrieve(rand());

        // Check if _origValues has the same values as the base object since the object hasn't been changed
        $productWithoutOrigValues = clone $product;
        unset($productWithoutOrigValues->_origValues);
        $this->assertSame($product->_origValues->id, $productWithoutOrigValues->id);
        $this->assertSame($product->_origValues->label, $productWithoutOrigValues->label);
        $this->assertSame($product->_origValues->reference, $productWithoutOrigValues->reference);

        $label = $product->label . 'A';
        $product->label = $label;
        $this->assertNotSame($product->label, $productWithoutOrigValues->label);

        $product = Product::update($product);
        $this->assertSame($product->label, $label);

        // Check if _origValues has correctly been changed
        $productWithoutOrigValues = clone $product;
        unset($productWithoutOrigValues->_origValues);
        $this->assertSame($product->_origValues->id, $productWithoutOrigValues->id);
        $this->assertSame($product->_origValues->label, $productWithoutOrigValues->label);
        $this->assertSame($product->_origValues->reference, $productWithoutOrigValues->reference);
    }

    public function testCanCreateMocked()
    {
        RestClient::getClient()->enableTesting();

        $product = new Product();
        $product->label = 'final fantasy';
        $product->reference = 'final-fantasy';
        $createdProduct = Product::create($product);

        $this->assertNotNull($createdProduct->id);
        $this->assertSame($createdProduct->label, 'final fantasy');
        $this->assertSame($createdProduct->reference, 'final-fantasy');
    }

    public function testCanDeleteMocked()
    {
        RestClient::getClient()->enableTesting();

        $this->assertNull(Product::delete(rand()));
    }

    /**
     * @expectedException \Exception
     */
    public function testAllApi()
    {
        RestClient::getClient()->disableTesting();

        $label = rand();
        $reference = $label + 1;

        // Test Create
        $product = new Product();
        $product->label = $label;
        $product->reference = $reference;
        $createdProduct = Product::create($product);

        $this->assertNotNull($createdProduct->id);
        $this->assertSame($createdProduct->label, $label);
        $this->assertSame($createdProduct->reference, $reference);

        // Test Retrieve
        $fetchedProduct = Product::retrieve($createdProduct->id);
        $this->assertSame($createdProduct, $fetchedProduct);

        // Test Update
        $fetchedProduct->label = $label + 2;
        $updatedProduct = Product::update($fetchedProduct);
        $this->assertNotSame($fetchedProduct, $updatedProduct);

        // Test Delete
        Product::delete($updatedProduct->id);
        // Must throw an Exception
        Product::retrieve($updatedProduct->id);
    }
}