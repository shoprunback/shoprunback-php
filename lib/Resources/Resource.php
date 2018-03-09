<?php

namespace Shoprunback\Resources;

use Shoprunback\Util\Logger;
use Shoprunback\Shoprunback;
use Shoprunback\RestClient;
use Shoprunback\Util\Inflector;

abstract class Resource
{
    public $id;

    public function __construct($id = '')
    {
        $this->id = $id;
        $this->_origValues = new \stdClass();
    }

    public static function indexEndpoint() {
        $className = explode('\\', get_called_class());
        return Inflector::pluralize(end($className));
    }

    public static function showEndpoint($id) {
        return self::indexEndpoint() . '/' . $id;
    }

    public static function createEndpoint() {
        return self::indexEndpoint();
    }

    public static function updateEndpoint($id) {
        return self::showEndpoint($id);
    }

    public static function deleteEndpoint($id) {
        return self::showEndpoint($id);
    }

    public function refresh()
    {
        $restClient = RestClient::getClient();
        $response = $restClient->request(self::showEndpoint($this->id), \Shoprunback\RestClient::GET);
        $this->copyValues($this->newFromMixed($response->getBody()));
    }

    public function put()
    {
        $restClient = RestClient::getClient();

        $dataToUpdate = new \stdClass();
        foreach ($this as $key => $value) {
            if (!isset($this->_origValues->$key) || $this->_origValues->$key != $value) {
                $dataToUpdate->$key = $value;
            }
        }

        unset($dataToUpdate->_origValues);

        $response = $restClient->request(self::updateEndpoint($this->id), \Shoprunback\RestClient::PUT, $dataToUpdate);
        $this->copyValues($this->newFromMixed($response->getBody()));
    }

    public function post()
    {
        $restClient = RestClient::getClient();
        $response = $restClient->request(self::createEndpoint(), \Shoprunback\RestClient::POST, $this);
        $this->copyValues($this->newFromMixed($response->getBody()));
    }

    public function remove()
    {
        $restClient = RestClient::getClient();
        $response = $restClient->request(self::deleteEndpoint($this->id), \Shoprunback\RestClient::DELETE);
    }

    public static function newFromMixed($mixed)
    {
        return Inflector::constantize($mixed, get_called_class());
    }

    public function copyValues($object)
    {
        foreach ($object as $key => $value) {
            if ($key != '_origValues') {
                $this->$key = $value;
            }
        }

        unset($this->_origValues);
        $this->_origValues = clone $this;
    }

    protected static function logCurrentClass($message)
    {
        $calledClassNameExploded = explode('\\', get_called_class());
        Logger::info(end($calledClassNameExploded) . ' ' . $message);
    }
}