<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

abstract class BaseResourceTest extends BaseTest
{
    abstract public static function getResourceClass();

    abstract public static function createDefault();

    abstract protected function checkIfHasNeededValues($object);
}