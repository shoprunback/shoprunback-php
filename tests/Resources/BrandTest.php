<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Shoprunback\Resources\Brand as Brand;
use \Tests\Resources\ApiObjectTest;

final class BrandTest extends ApiObjectTest
{
    public static function getClass()
    {
        return 'Brand';
    }

    public static function getObjectSample()
    {
        $brand = new Brand();
        $brand->name = 'Test Brand';
        $brand->reference = 'test-brand-' . date('Y-m-d-h-i');

        return $brand;
    }

    public static function updateForTest($brand)
    {
        $brand->name = 'Test Brand but it\'s even more original';
        return $brand;
    }

    public static function getObjectToFetch()
    {
        return self::getObjectSample()->reference;
    }
}