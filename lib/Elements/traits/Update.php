<?php

namespace Shoprunback\Elements;

use Shoprunback\RestClient;

trait Update
{
    public static function update($element)
    {
        $element->put();
        return $element;
    }

    public function put()
    {
        $restClient = RestClient::getClient();
        $data = $this->getElementBody();
        $response = $restClient->request(self::updateEndpoint($this->id), RestClient::PUT, $data);
        $this->copyValues($this->newFromMixed($response->getBody()));
    }
}
