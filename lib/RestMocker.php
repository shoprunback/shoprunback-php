<?php

namespace Shoprunback;

use Shoprunback\Error\Error;
use Shoprunback\Error\ReferenceTaken;
use Shoprunback\Error\UnknownApiToken;
use Shoprunback\Util\Logger;
use Shoprunback\RestClient;
use Shoprunback\RestResponse;

class RestMocker
{

    public static function request($endpoint, $method = RestClient::GET, $body = [])
    {
        throw new Exception('TODO');
    }

}