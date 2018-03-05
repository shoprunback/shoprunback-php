<?php

declare(strict_types=1);

namespace Tests;

use \Tests\BaseTest;

use \Shoprunback\Shoprunback;

final class ShoprunbackTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testIsSetup()
    {
        $this->assertTrue(Shoprunback::isSetup());
    }
}