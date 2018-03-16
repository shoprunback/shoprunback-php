<?php

namespace Shoprunback\Resources;

use Shoprunback\Pagination;
use Shoprunback\RestClient;
use Shoprunback\Util\Inflector;

trait All
{
    public static function all($page = 1)
    {
        $restClient = RestClient::getClient();
        $response = $restClient->request(self::indexEndpoint($page), \Shoprunback\RestClient::GET);

        # TODO Quelle page c'est ? S'il y a une prochaine page ? Combien de produits en tout ?
        $responseBody = $response->getBody();
        $pagination = new Pagination();
        if (isset($responseBody->pagination)) {
            $pagination->current_page   = $responseBody->pagination->current_page;
            $pagination->next_page      = $responseBody->pagination->next_page;
            // $pagination->last_page      = $responseBody->pagination->last_page;
            $pagination->count          = $responseBody->pagination->count;

            $resourceAttributeName = self::getResourceName() . 's';
            $responseBody = $responseBody->$resourceAttributeName;
        }

        $instances = [];
        foreach ($responseBody as $resource) {
            $instances[] = self::newFromMixed($resource);
        }

        return [$instances, $pagination];
    }
}
