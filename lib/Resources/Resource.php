<?php

namespace Shoprunback\Resources;

use Shoprunback\Util\Logger;
use Shoprunback\Shoprunback;
use Shoprunback\RestClient;
use Shoprunback\Util\Inflector;
use Shoprunback\Error\NotFoundError;
use Shoprunback\Error\RestClientError;

abstract class Resource
{
    public $id;

    public function __construct($id = '')
    {
        $this->id = $id;
        $this->_origValues = new \stdClass();
    }

    abstract static function getBelongsTo();

    abstract static function getAcceptNestedAttributes();

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

        try {
            $response = $restClient->request(self::showEndpoint($this->id), \Shoprunback\RestClient::GET);
        } catch(RestClientError $e) {
            self::logCurrentClass(json_encode($e));
            if ($e->response->getCode() == 404) {
                throw new NotFoundError('Not found');
            } else {
                throw $e;
            }
        }

        $this->copyValues($this->newFromMixed($response->getBody()));
    }

    public function save()
    {
        if ($this->isPersisted()) {
            $this->put();
        } else {
            $this->post();
        }
    }

    private function post()
    {
        $restClient = RestClient::getClient();
        $data = $this->formatResourceForApi();
        $response = $restClient->request(self::createEndpoint(), \Shoprunback\RestClient::POST, $data);
        $this->copyValues($this->newFromMixed($response->getBody()));
    }

    private function put()
    {
        $restClient = RestClient::getClient();
        $data = $this->formatResourceForApi();
        $response = $restClient->request(self::updateEndpoint($this->id), \Shoprunback\RestClient::PUT, $data);
        $this->copyValues($this->newFromMixed($response->getBody()));
    }

    public function remove()
    {
        $restClient = RestClient::getClient();
        $this->refresh();
        self::logCurrentClass('Log of the object before its removal: ' . json_encode($this->_origValues));
        $response = $restClient->request(self::deleteEndpoint($this->id), \Shoprunback\RestClient::DELETE);
    }

    public function formatResourceForApi()
    {
        foreach (static::getBelongsTo() as $parent) {
            $parentId = $parent . '_id';

            if (!$this->$parent->isPersisted()) {
                if (!in_array($parent, static::getAcceptNestedAttributes())) {
                    $this->$parent->save();
                    $this->$parentId = $this->$parent->id;
                    unset($this->$parent);
                }
            } else {
                if (!isset($this->$parentId) || empty($this->$parentId)) {
                    $this->$parentId = $this->$parent->id;
                }

                unset($this->$parent);
            }
        }

        $data = new \stdClass();
        foreach ($this as $key => $value) {
            // Check if we need to take the value
            if (!isset($this->_origValues->$key) || $this->_origValues->$key != $value) {
                $data->$key = self::getChildren($key, $value);
            }
        }

        unset($data->_origValues);

        return $data;
    }

    private function getChildren($key, $value)
    {
        if (Inflector::isKnownResource($key)) { // If it is a resource
            return $value->formatResourceForApi();
        } elseif (Inflector::isPluralClassName($key, rtrim($key, 's'))) { // If it is an array of resources
            $arrayOfResources = [];

            foreach ($value as $k => $resource) {
                $arrayOfResources[] = $resource->formatResourceForApi();
            }

            return $arrayOfResources;
        } else {
            return $value;
        }
    }

    public static function newFromMixed($mixed)
    {
        $resource = Inflector::constantize($mixed, get_called_class());
        $resource->copyValues($resource);
        return $resource;
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

    public function isPersisted()
    {
        return (isset($this->_origValues) && isset($this->_origValues->id));
    }

    protected static function logCurrentClass($message)
    {
        $calledClassNameExploded = explode('\\', get_called_class());
        Logger::info(end($calledClassNameExploded) . ': ' . $message);
    }

    public function getOriginalValues()
    {
        return $this->_origValues;
    }
}