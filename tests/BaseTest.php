<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    public function setUp()
    {
        require_once dirname(__FILE__, 2) . '/init.php';
    }

    protected static function randomString()
    {
        return get_called_class() . '-' . uniqid();
    }
}