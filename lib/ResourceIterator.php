<?php

namespace Shoprunback;

use Shoprunback\Error\ResourceNumberDoesntExists;

class ResourceIterator extends \ArrayObject
{
    public function __construct($responseBody, $resourceName)
    {
        $this->per_page = 10;

        if (isset($responseBody->pagination)) {
            $this->count        = $responseBody->pagination->count;
            $this->per_page     = $responseBody->pagination->per_page;
            $this->next_page    = $responseBody->pagination->next_page;
            $this->current_page = $responseBody->pagination->current_page;

            $resourceKey = $resourceName::getAllResourceKey();
            foreach ($responseBody->$resourceKey as $resource) {
                $this->resources[] = $resourceName::newFromMixed($resource);
            }
        } else {
            $this->count = count($responseBody);

            foreach ($responseBody as $resource) {
                $this->resources[] = $resourceName::newFromMixed($resource);
            }
        }
    }

    public function getIterator()
    {
        $i = 1;
        $resourceIterator = self::getResourceName()::all();
        $resources = $resourceIterator->resources;

        while (isset($resourceIterator->next_page) && !is_null($resourceIterator->next_page)) {
            $i++;
            $resourceIterator = self::getResourceName()::all($i);

            foreach ($resourceIterator->resources as $resource) {
                $resources[] = $resource;
            }
        }

        return new \ArrayIterator($resources);
    }

    public function offsetGet($id)
    {
        if (isset($this->resources[$id])) {
            return $this->resources[$id];
        }

        if ($id < $this->count) {
            return self::getResourceName()::all(floor($id / $this->per_page))[$id % $this->per_page];
        }

        throw new ResourceNumberDoesntExists('There is ' . $this->count . ' ' . self::getResourceName()::getResourceName() . ' and the number asked was ' . $id);
    }

    public function getResourceName()
    {
        return get_class($this[0]);
    }
}