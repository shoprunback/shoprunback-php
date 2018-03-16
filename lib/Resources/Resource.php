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

    public function __set($key, $value)
    {
        if ($this->belongsTo($key)) {
            $attributeId = $key . '_id';
            $this->$attributeId = $value->id;
        }

        $this->$key = $value;
    }

    abstract static function getBelongsTo();

    abstract static function getAcceptNestedAttributes();

    public function belongsTo($key)
    {
        return in_array($key, static::getBelongsTo());
    }

    public function acceptNestedAttribute($key)
    {
        return in_array($key, static::getAcceptNestedAttributes());
    }

    public function display($resourceString)
    {
        return $resourceString . ' (' . $this->id . ')';
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
        $data = $this->getResourceBody();
        $response = $restClient->request(self::createEndpoint(), \Shoprunback\RestClient::POST, $data);
        $this->copyValues($this->newFromMixed($response->getBody()));
    }

    private function put()
    {
        $restClient = RestClient::getClient();
        $data = $this->getResourceBody();
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

    public function printResourceBody()
    {
        echo $this . ': ' . json_encode($this->getResourceBody(false)) . "\n";
    }

    public function getDirtyKeys()
    {
        $dirtyKeys = [];
        foreach ($this as $key => $value) {
            if (
                $key != '_origValues'
                && $key != 'id'
                && $this->isKeyDirty($key)
            ) {
                $dirtyKeys[] = $key;
            } elseif (!Inflector::isKnownResource($key)) {
                $keyPreged = preg_replace('/_id$/', '', $key);

                if ($keyPreged != $key && Inflector::isKnownResource($keyPreged) && $this->$keyPreged->id != $value) {
                    if (!empty($this->$keyPreged->id) && $this->$keyPreged->id != $this->_origValues->$key) {
                        $dirtyKeys[] = $key;
                    }

                    $keyToUnset = array_search($keyPreged, $dirtyKeys);
                    if ($keyToUnset && !$this->$keyPreged->isDirty()) {
                        unset($dirtyKeys[$keyToUnset]);
                    }
                }
            }

            // If nested resource is a different one, but an unchanged one
            $keyToUnset = array_search($key, $dirtyKeys);
            if ($keyToUnset && Inflector::isKnownResource($key) && !$value->isDirty()) {
                unset($dirtyKeys[$keyToUnset]);
            }
        }

        return $dirtyKeys;
    }

    public function isDirty()
    {
        foreach ($this as $key => $value) {
            if ($key != '_origValues' && $this->isKeyDirty($key)) {
                return true;
            }
        }

        return false;
    }

    public function isKeyDirty($key)
    {
        if (Inflector::isKnownResource($key)) {
            return $this->$key->isDirty() || $this->checkIfDirty($this->{$key . '_id'});
        } elseif (Inflector::isPluralClassName($key, rtrim($key, 's'))) {
            foreach ($this->$key as $value) {
                if ($value->isDirty()) {
                    return true;
                }
            }

            return false;
        }

        return $this->checkIfDirty($key);
    }

    public function checkIfDirty($key)
    {
        return !property_exists($this->_origValues, $key) || $this->$key != $this->_origValues->$key;
    }

    public function getResourceBody($save = true)
    {
        // #TODO manage belongsTo and belongsToOptional
        foreach (static::getBelongsTo() as $parent) {
            if (!property_exists($this, $parent)) {
                continue;
            }

            $parentId = $parent . '_id';

            if (property_exists($this->$parent, 'id') && !empty($this->$parent->id)) {
                $this->$parentId = $this->$parent->id;
            }

            if (!$this->$parent->isPersisted()) {
                if (!in_array($parent, static::getAcceptNestedAttributes()) && $save) {
                    $this->$parent->save();
                }
            } elseif ($this->$parent->isDirty() && $save) {
                $this->$parent->save();
            }
        }

        $data = new \stdClass();
        foreach ($this as $key => $value) {
            $keyPreged = preg_replace('/_id$/', '', $key);

            if (
                $key != '_origValues'
                && $this->isKeyDirty($key)
                && (
                    !isset($this->{$key . '_id'})
                    || (
                        !$save
                        && $value->isDirty()
                    )
                )
                && (
                    $keyPreged == $key
                    || (
                        Inflector::isKnownResource($keyPreged)
                        && property_exists($this->$keyPreged, 'id')
                        && !empty($this->$keyPreged->id)
                    )
                )
            ) {
                $data->$key = self::getChildren($key, $value);
            }
        }

        unset($data->id);
        unset($data->_origValues);

        return $data;
    }

    private function getChildren($key, $value)
    {
        if (Inflector::isKnownResource($key)) { // If it is a resource
            return $value->getResourceBody();
        } elseif (Inflector::isPluralClassName($key, rtrim($key, 's'))) { // If it is an array of resources
            $arrayOfResources = [];

            foreach ($value as $k => $resource) {
                $arrayOfResources[] = $resource->getResourceBody();
            }

            return $arrayOfResources;
        }

        return $value;
    }

    public static function newFromMixed($mixed)
    {
        $resource = Inflector::constantize($mixed, get_called_class());
        foreach ($resource as $key => $value) {
            if (is_object($value) && Inflector::isKnownResource($key)) {
                $class = get_class($value);
                $resource->$key = $class::newFromMixed($value);
            }
        }
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