<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use \Tests\BaseTest;

use \Shoprunback\Shoprunback;

final class ShoprunbackTest extends BaseTest
{
    public function __construct()
    {
        parent::__construct();

        // TODO Find how to get those in env
        Shoprunback::setApiBaseUrl('http://localhost:3000/api/v1/');
        Shoprunback::setApiToken('UD3RW8zx7WKe75KWz96Z6YroCXeUrPzyPsZxfVsyLhRDM8xWoA');
    }
}