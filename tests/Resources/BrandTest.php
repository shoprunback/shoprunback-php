<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Shoprunback\Resources\Brand as Brand;
use \Tests\Resources\ApiObjectTest;
use \Tests\Mocker;

final class BrandTest extends ApiObjectTest
{
    public static function getClass()
    {
        return 'Brand';
    }

    public static function getClassId()
    {
        return '1234';
    }

    public static function getClassReference()
    {
        return 'test-brand';
    }

    public static function getMockerJson()
    {
        return Mocker::getBrandJson();
    }
}