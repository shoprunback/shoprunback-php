<?php

namespace Tests\Elements;

use \Tests\BaseTest;

use \Shoprunback\RestClient;
use \Shoprunback\Elements\Brand;
use \Shoprunback\Util\Inflector;
use \Shoprunback\Util\Container;

final class InflectorTest extends BaseTest
{
    public function testIsKnownElement()
    {
        $this->assertTrue(Inflector::isKnownElement('Brand'));
        $this->assertTrue(Inflector::isKnownElement('brand'));
        $this->assertFalse(Inflector::isKnownElement('Brands'));
    }

    public function testClassify()
    {
        $this->assertSame(Inflector::classify('Brand'), 'Brand');
        $this->assertSame(Inflector::classify('Brands'), 'Brand');
        $this->assertSame(Inflector::classify('Country'), 'Country');
        $this->assertSame(Inflector::classify('Countries'), 'Country');
    }

    public function testPluralize()
    {
        $this->assertSame(Inflector::pluralize('Brand'), 'brands');
        $this->assertSame(Inflector::pluralize('Brands'), 'brands');
        $this->assertSame(Inflector::pluralize('Country'), 'countries');
        $this->assertSame(Inflector::pluralize('Countries'), 'countries');
    }

    public function testIsPluralClassName()
    {
        $this->assertTrue(Inflector::isPluralClassName('Brand', 'brands'));
        $this->assertTrue(Inflector::isPluralClassName('Brands', 'brands'));
        $this->assertTrue(Inflector::isPluralClassName('Country', 'countries'));
        $this->assertTrue(Inflector::isPluralClassName('Countries', 'countries'));
    }

    public function testConstantizeOne()
    {
        RestClient::getClient()->enableTesting();
        $retrievedBrand = Brand::retrieve(1);

        $name = self::randomString();
        $reference = self::randomString();
        $arrayBrand = ['name' => $name, 'reference' => $reference];
        $objectBrand = new \stdClass();
        $objectBrand->name = $name;
        $objectBrand->reference = $reference;

        $inflectedArrayBrand = Inflector::constantize($arrayBrand, 'Brand');
        $inflectedObjectBrand = Inflector::constantize($objectBrand, 'Brand');

        $this->assertSame($inflectedArrayBrand->name, $name);
        $this->assertSame($inflectedObjectBrand->name, $name);

        $this->assertSame($inflectedArrayBrand->reference, $reference);
        $this->assertSame($inflectedObjectBrand->reference, $reference);

        $this->assertEquals($inflectedArrayBrand->_origValues, $inflectedObjectBrand->_origValues);
    }
}