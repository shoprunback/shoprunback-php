<?php

namespace Shoprunback\Resources;

use Shoprunback\ResourceManager;
use Shoprunback\RestClient;
use Shoprunback\Util\Inflector;

trait All
{
    public static function all($page = 1)
    {
        $restClient = RestClient::getClient();
        $response = $restClient->request(self::indexEndpoint($page), \Shoprunback\RestClient::GET);

        return new ResourceManager($response->getBody(), get_called_class());
    }
}
