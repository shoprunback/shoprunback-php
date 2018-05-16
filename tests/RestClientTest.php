<?php

namespace Tests;

use \Tests\BaseTest;

use \Shoprunback\RestClient;

final class RestClientTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();
    }

    public function tearDown()
    {
        \Shoprunback\RestClient::getClient()->resetClient();
    }

    public function testSetBaseApiUrl()
    {
        $restClient = \Shoprunback\RestClient::getClient();

        $restClient->setApiBaseUrl('http://localhost:3000');
        $this->assertSame($restClient->getApiBaseUrl(), 'http://localhost:3000');
    }

    public function testGetApiFullUrl()
    {
        $restClient = \Shoprunback\RestClient::getClient();

        $restClient->setApiBaseUrl('http://localhost:3000');
        $this->assertSame($restClient->getApiFullUrl(), 'http://localhost:3000/api/v1/');
    }

    public function testUseSandboxEnvironment()
    {
        $restClient = \Shoprunback\RestClient::getClient();

        $restClient->useSandboxEnvironment();
        $this->assertSame($restClient->getApiBaseUrl(), 'https://sandbox.dashboard.shoprunback.com');
        $this->assertSame($restClient->getApiFullUrl(), 'https://sandbox.dashboard.shoprunback.com/api/v1/');
    }

    public function testUseProductionEnvironment()
    {
        $restClient = \Shoprunback\RestClient::getClient();

        $restClient->useProductionEnvironment();
        $this->assertSame($restClient->getApiBaseUrl(), 'https://dashboard.shoprunback.com');
        $this->assertSame($restClient->getApiFullUrl(), 'https://dashboard.shoprunback.com/api/v1/');
    }
}