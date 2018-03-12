<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Shoprunback\RestClient;
use \Shoprunback\Resources\Brand;
use \Shoprunback\Util\Logger;

final class LoggerTest extends BaseTest
{
    public function testCanCreateFile()
    {
        Logger::info('Test');
        $this->assertTrue(file_exists(Logger::getFullPathToFile()));
    }

    public function testCanLogError()
    {
        Logger::error('Error');
        $this->assertTrue(strpos(Logger::getLastLine(), 'Error') > 0);
    }

    public function testCanLogResource()
    {
        RestClient::getClient()->enableTesting();
        Brand::delete(1);

        $this->assertTrue(strpos(Logger::getLastLine(), date(Logger::LINE_PREFIX_DATE_FORMAT) . 'Brand:') === 0);
    }
}