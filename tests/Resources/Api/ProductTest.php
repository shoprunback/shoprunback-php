<?php

declare(strict_types=1);

namespace Tests\Resources\Api;

use \Tests\Resources\Api\BaseApiTest;

use \Shoprunback\Resources\Product;
use \Shoprunback\RestClient;
use \Shoprunback\Error\NotFoundError;

final class ProductTest extends BaseApiTest
{
    use \Tests\Resources\ProductTrait;

    public function testBrandFromApiIsPersisted()
    {
        RestClient::getClient()->disableTesting();

        $reference = strval(rand());
        $product = self::createDefault();
        $this->assertFalse($product->isPersisted());

        $product->save();
        $this->assertTrue($product->isPersisted());
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