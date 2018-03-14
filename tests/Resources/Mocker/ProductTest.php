<?php

declare(strict_types=1);

namespace Tests\Resources\Mocker;

use \Tests\Resources\Mocker\BaseMockerTest;

use \Shoprunback\Resources\Product;
use \Shoprunback\RestClient;

final class ProductTest extends BaseMockerTest
{
    use \Tests\Resources\ProductTrait;

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
}