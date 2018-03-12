<?php

namespace Shoprunback\Resources;

use Shoprunback\RestClient;
use Shoprunback\Util\Inflector;

trait All
{
    #TODO paginated resources
    public static function all($page = 1)
    {
        $restClient = RestClient::getClient();
        $response = $restClient->request(self::indexEndpoint(), \Shoprunback\RestClient::GET);

        $responseBody = $response->getBody();
        if (isset($responseBody->pagination)) {
            $responseBody = $responseBody->products;
        }

        $instances = [];
        foreach ($responseBody as $resource) {
            $instances[] = self::newFromMixed($resource);
        }

        return $instances;
    }
}
