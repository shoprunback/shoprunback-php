<?php

namespace Shoprunback;

use Shoprunback\Error\ResourceNumberDoesntExists;
use Shoprunback\ResourceIterator;

class ResourceManager extends \ArrayObject
{
    public function __construct($responseBody, $resourceName)
    {
        $this->per_page     = 10;
        $this->next_page    = null;
        $this->current_page = 1;

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

    public function count()
    {
        return count($this->resources);
    }

    public function getIterator()
    {
        return new ResourceIterator($this);
    }

    public function offsetGet($id)
    {
        if (isset($this->resources[$id])) {
            return $this->resources[$id];
        }

        if ($id < $this->count) {
            return $this->getResourceClass()::all(floor($id / $this->per_page))[$id % $this->per_page];
        }

        throw new ResourceNumberDoesntExists('There is ' . $this->count . ' ' . $this->getResourceClass()::getAllResourceKey() . ' and the number asked was ' . $id);
    }

    public function getResourceClass()
    {
        return get_class($this[0]);
    }
}