<?php

namespace Shoprunback\Elements;

use Shoprunback\Shoprunback;
use Shoprunback\RestClient;
use Shoprunback\Util\Inflector;
use Shoprunback\Util\Logger;
use Shoprunback\Error\NotFoundError;
use Shoprunback\Error\RestClientError;
use Shoprunback\Elements\NestedAttributes;

abstract class Element implements NestedAttributes
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

    public function belongsTo($key)
    {
        return in_array($key, static::getBelongsTo());
    }

    public function acceptNestedAttribute($key)
    {
        return in_array($key, static::getAcceptNestedAttributes());
    }

    public function display($elementString)
    {
        return $elementString . ' (' . $this->id . ')';
    }

    public static function indexEndpoint($page = 1) {
        $className = explode('\\', get_called_class());
        $endpoint = Inflector::pluralize(end($className));

        if ($page > 1) {
            $endpoint .= '?page=' . $page;
        }

        return $endpoint;
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
            if (static::canUpdate()) {
                $this->put();
            } else {
                throw new ElementCannotBeUpdated(get_called_class() . ' cannot be updated');
            }
        } else {
            $this->post();
        }
    }

    private function post()
    {
        $restClient = RestClient::getClient();
        $data = $this->getElementBody();
        $response = $restClient->request(self::createEndpoint(), \Shoprunback\RestClient::POST, $data);
        $this->copyValues($this->newFromMixed($response->getBody()));
    }

    public function printElementBody()
    {
        echo $this . ': ' . json_encode($this->getElementBody(false)) . "\n";
    }

    public function getDirtyKeys()
    {
        $dirtyKeys = [];
        foreach ($this as $key => $value) {
            if (
                $key != 'id'
                && $this->isKeyDirty($key)
            ) {
                $dirtyKeys[] = $key;
            } elseif (!Inflector::isKnownElement($key)) {
                $keyPreged = preg_replace('/_id$/', '', $key);

                if ($keyPreged != $key && Inflector::isKnownElement($keyPreged) && $this->$keyPreged->id != $value) {
                    if (!empty($this->$keyPreged->id) && $this->$keyPreged->id != $this->_origValues->$key) {
                        $dirtyKeys[] = $key;
                    }

                    $keyToUnset = array_search($keyPreged, $dirtyKeys);
                    if ($keyToUnset && !$this->$keyPreged->isDirty()) {
                        unset($dirtyKeys[$keyToUnset]);
                    }
                }
            }

            // If nested element is a different one, but an unchanged one
            $keyToUnset = array_search($key, $dirtyKeys);
            if ($keyToUnset && Inflector::isKnownElement($key) && !$value->isDirty()) {
                unset($dirtyKeys[$keyToUnset]);
            }
        }

        return $dirtyKeys;
    }

    public function isDirty()
    {
        foreach ($this as $key => $value) {
            if ($this->isKeyDirty($key)) {
                return true;
            }
        }

        return false;
    }

    public function isKeyDirty($key)
    {
        if ($key == '_origValues') {
            return false;
        }

        if (Inflector::isKnownElement($key)) {
            return $this->$key->isDirty() || (!$this->$key::canOnlyBeNested() && $this->checkIfDirty($key . '_id'));
        } elseif (Inflector::isPluralClassName($key, rtrim($key, 's'))) {
            foreach ($this->$key as $value) {
                if ($value->isDirty()) {
                    return true;
                }
            }

            return false;
        }

        $keyPreged = preg_replace('/_id$/', '', $key);
        if (
            $keyPreged != $key
            && Inflector::isKnownElement($keyPreged)
            && isset($this->$keyPreged->id)
            && !empty($this->$keyPreged->id)
            && $this->$key != $this->$keyPreged->id
        ) {
            return true;
        }

        return $this->checkIfDirty($key);
    }

    public function checkIfDirty($key)
    {
        return !property_exists($this->_origValues, $key) || $this->$key != $this->_origValues->$key;
    }

    public function getElementBody($save = true)
    {
        // #TODO manage belongsTo and belongsToOptional
        foreach (static::getBelongsTo() as $parent) {
            if (!property_exists($this, $parent)) {
                continue;
            }

            $parentId = $parent . '_id';

            if (property_exists($this->$parent, 'id') && !empty($this->$parent->id) && !$this->isKeyDirty($parent)) {
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
                $this->isKeyDirty($key)
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
                        Inflector::isKnownElement($keyPreged)
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
        if (Inflector::isKnownElement($key)) { // If it is a element
            return $value->getElementBody();
        } elseif (Inflector::isPluralClassName($key, rtrim($key, 's'))) { // If it is an array of elements
            $arrayOfElements = [];

            foreach ($value as $k => $element) {
                $arrayOfElements[] = $element->getElementBody();
            }

            return $arrayOfElements;
        }

        return $value;
    }

    public static function newFromMixed($mixed)
    {
        $element = Inflector::constantize($mixed, get_called_class());
        foreach ($element as $key => $value) {
            if (is_object($value) && Inflector::isKnownElement($key)) {
                $class = get_class($value);
                $element->$key = $class::newFromMixed($value);
            }
        }
        $element->copyValues($element);
        return $element;
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

    public static function getElementName()
    {
        $className = get_called_class();
        $explode = explode('\\', $className);
        return strtolower(end($explode));
    }

    public static function getAllElementKey()
    {
        return static::getElementName() . 's';
    }

    public static function canDelete()
    {
        return method_exists(get_called_class(), 'delete');
    }

    public static function canUpdate()
    {
        return method_exists(get_called_class(), 'update');
    }
}
