<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

use Shoprunback\Shoprunback;

class BaseTest extends TestCase
{
    public function __construct()
    {
        parent::__construct();
        require_once dirname(__FILE__) . '/../init.php';

        // TODO Set those var in env
        // Shoprunback::setApiBaseUrl(getenv('DASHBOARD_URL') . '/api/v1/');
        // Shoprunback::setApiToken(getenv('SHOPRUNBACK_API_TOKEN'));
    }

    public function testConstructed()
    {
        $this->assertEquals(0, 2-2);
    }
}