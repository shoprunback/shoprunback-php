<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

use Shoprunback\Shoprunback;

class BaseTest extends TestCase
{
    /** @test */
    public function __construct()
    {
        parent::__construct();
        require_once dirname(dirname(__FILE__)) . '/init.php';

        if (Shoprunback::getApiBaseUrl() == 'https://dashboard.shoprunback.com/api/v1/' && is_null(Shoprunback::getApiToken())) {
            $this->assertTrue(self::loadConfig());
        } else {
            $this->assertTrue(Shoprunback::isSetup());
        }
    }

    public static function loadConfig()
    {
        if (getenv('DASHBOARD_URL') && getenv('SHOPRUNBACK_API_TOKEN')) {
            Shoprunback::setApiBaseUrl(getenv('DASHBOARD_URL') . '/api/v1/');
            Shoprunback::setApiToken(getenv('SHOPRUNBACK_API_TOKEN'));

            return true;
        }

        return false;
    }
}