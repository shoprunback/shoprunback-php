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
        require_once dirname(__FILE__, 2) . '/init.php';
    }
}