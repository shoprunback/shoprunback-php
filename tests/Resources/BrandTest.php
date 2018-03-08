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
}