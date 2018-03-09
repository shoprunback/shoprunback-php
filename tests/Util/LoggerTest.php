<?php

declare(strict_types=1);

namespace Tests\Resources;

use \Tests\BaseTest;

use \Shoprunback\RestClient;

final class BrandTest extends BaseTest
{
    const CLASS_NAME = 'Shoprunback\Resources\Brand';

    public function testCanFetchOneMocked()
    {
        RestClient::getClient()->enableTesting();

        $this->checkIfHasNeededValues($brand);
    }
}