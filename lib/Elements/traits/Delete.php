<?php

namespace Shoprunback\Elements;

use Shoprunback\RestClient;

trait Delete
{
    public static function delete($id)
    {
        $instance = new static($id);
        return $instance->remove();
    }

    public function remove()
    {
        $this->refresh();
        self::logCurrentClass('Log of the object before its removal: ' . json_encode($this->_origValues));
        RestClient::getClient()->request(self::deleteEndpoint($this->id), \Shoprunback\RestClient::DELETE);
    }
}
