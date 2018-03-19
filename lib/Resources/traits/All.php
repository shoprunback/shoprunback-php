<?php

namespace Shoprunback\Resources;

use Shoprunback\ResourceIterator;
use Shoprunback\RestClient;
use Shoprunback\Util\Inflector;

trait All
{
    public static function all($page = 1)
    {
        $restClient = RestClient::getClient();
        $response = $restClient->request(self::indexEndpoint($page), \Shoprunback\RestClient::GET);

        # TODO Quelle page c'est ? S'il y a une prochaine page ? Combien de produits en tout ?
        return new ResourceIterator($response->getBody(), get_called_class());
    }
}
