<?php

namespace Tests\Elements\Mocker;

use \Tests\Elements\Mocker\BaseMockerTest;

use \Shoprunback\Elements\Company;
use \Shoprunback\RestClient;

final class CompanyTest extends BaseMockerTest
{
    use \Tests\Elements\CompanyTrait;

    public function testCanUpdateOneMocked()
    {
        $company = Company::retrieve(1);
        $company->name = self::randomString();
        $company->save();

        $retrievedCompany = Company::retrieve(1);

        $this->assertNotSame($retrievedCompany->name, $company->name);
    }

    public function testGetChangedCompanyBody()
    {
        RestClient::getClient()->enableTesting();

        $company = Company::retrieve(1);

        $company->name = static::randomString();
        $elementBody = $company->getElementBody(false);
        $this->assertTrue(property_exists($elementBody, 'name'));
        $this->assertEquals(count(get_object_vars($elementBody)), 1);
    }

    public function testGetNewCompanyBody()
    {
        RestClient::getClient()->enableTesting();

        $company = new Company();

        $company->name = static::randomString();
        $elementBody = $company->getElementBody(false);
        $this->assertTrue(property_exists($elementBody, 'name'));
        $this->assertEquals(count(get_object_vars($elementBody)), 1);

        $company->slug = static::randomString();
        $elementBody = $company->getElementBody(false);
        $this->assertTrue(property_exists($elementBody, 'slug'));
        $this->assertEquals(count(get_object_vars($elementBody)), 2);
    }

    public function testCanSaveMocked()
    {
        $this->assertTrue(true);
    }
}