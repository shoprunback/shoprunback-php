<?php

declare(strict_types=1);

namespace Tests\Resources;

use PHPUnit\Framework\TestCase;
use \Tests\BaseTest;

use \Shoprunback\Resources\Brand;

final class BrandTest extends BaseTest
{
    public function testCanFetchBrand()
    {
        $this->assertInstanceOf(
            Brand::class,
            Brand::fetch('Fashion-Manufacturer')
        );
    }
}