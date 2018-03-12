<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Shoprunback\RestClient;
use \Shoprunback\Resources\Brand;
use \Shoprunback\Util\Inflector;

final class InflectorTest extends BaseTest
{
    public function testIsKnownResource()
    {
        $this->assertTrue(Inflector::isKnownResource('Brand'));
        $this->assertTrue(Inflector::isKnownResource('brand'));
        $this->assertFalse(Inflector::isKnownResource('Brands'));
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

    public function testAddToMixed()
    {
        $array = [];
        $key = 'key';
        $value = 'value';
        Inflector::addValueToMixed($array, $key, $value);
        $this->assertSame($array[$key], $value);

        $object = new \stdClass();
        Inflector::addValueToMixed($object, $key, $value);
        $this->assertSame($object->$key, $value);

        $this->assertSame($array[$key], $object->$key);
    }

    public function testIsContainer()
    {
        $this->assertTrue(Inflector::isContainer([1]));
        $this->assertTrue(Inflector::isContainer(new \stdClass()));
        $this->assertFalse(Inflector::isContainer('a'));
        $this->assertFalse(Inflector::isContainer(1));
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
        $objectBrand->reference = $name;

        $inflectedArrayBrand = Inflector::($arrayBrand, 'Brand');
        $inflectedObjectBrand = Inflector::($objectBrand, 'Brand');

        $this->assertSame($inflectedArrayBrand->name, $name);
        $this->assertSame($inflectedObjectBrand->name, $name);

        $this->assertSame($inflectedArrayBrand->reference, $reference);
        $this->assertSame($inflectedObjectBrand->reference, $reference);

        $this->assertSame($inflectedArrayBrand->_origValues, $inflectedObjectBrand->_origValues);
    }
}