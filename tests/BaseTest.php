<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

use Shoprunback\Shoprunback;

class BaseTest extends TestCase
{
    public function setUp()
    {
        require_once dirname(__FILE__, 2) . '/init.php';
    }

    public function testNothing()
    {
      $this->assertEquals(0, 0);
    }
}