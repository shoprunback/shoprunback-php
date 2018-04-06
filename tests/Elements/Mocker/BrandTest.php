<?php

namespace Tests\Elements\Mocker;

require_once dirname(__FILE__) . '/../../../init.php';

use \Tests\Elements\Mocker\BaseMockerTest;

use \Shoprunback\Elements\Brand;
use \Shoprunback\RestClient;

// To test getClass
class BrandChild extends \Shoprunback\Elements\Brand
{}
class BrandChildChild extends BrandChild
{}

final class BrandTest extends BaseMockerTest
{
    use \Tests\Elements\BrandTrait;

    public function testCanUpdateOneMocked()
    {
        RestClient::getClient()->enableTesting();

        $brand = Brand::retrieve(1);
        $brand->name = self::randomString();
        $brand->save();

        $retrievedBrand = Brand::retrieve(1);

        $this->assertNotSame($retrievedBrand->name, $brand->name);
    }

    public function testGetChangedBrandBody()
    {
        RestClient::getClient()->enableTesting();

        $brand = Brand::retrieve(1);

        $brand->name = static::randomString();
        $elementBody = $brand->getElementBody(false);
        $this->assertTrue(property_exists($elementBody, 'name'));
        $this->assertEquals(count(get_object_vars($elementBody)), 1);
    }

    public function testGetNewBrandBody()
    {
        RestClient::getClient()->enableTesting();

        $brand = new Brand();

        $brand->name = static::randomString();
        $elementBody = $brand->getElementBody(false);
        $this->assertTrue(property_exists($elementBody, 'name'));
        $this->assertEquals(count(get_object_vars($elementBody)), 1);

        $brand->reference = static::randomString();
        $elementBody = $brand->getElementBody(false);
        $this->assertTrue(property_exists($elementBody, 'reference'));
        $this->assertEquals(count(get_object_vars($elementBody)), 2);
    }
}