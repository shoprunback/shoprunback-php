<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Shoprunback\RestClient;
use \Shoprunback\Resources\Brand;
use \Shoprunback\Util\Logger;

define('LOG_FILENAME', date('Y-m-d') . '.txt');
define('TESTS_LOG_PATH', dirname(__FILE__, 3) . '/logs/');

final class LoggerTest extends BaseTest
{
    const CLASS_NAME = 'Shoprunback\Util\Logger';
    const PATH_TO_LOG_FILE = TESTS_LOG_PATH . LOG_FILENAME;

    public function testCanCreateFile()
    {
        Logger::info('Test');
        $this->assertTrue(file_exists(self::PATH_TO_LOG_FILE));
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