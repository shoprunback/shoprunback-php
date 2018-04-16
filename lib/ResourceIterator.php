<?php

namespace Shoprunback;

use Shoprunback\Error\ResourceNumberDoesntExists;

class ResourceIterator implements \Iterator, \ArrayAccess
{
    // Implements Iterator
    private $position = 0;
    public $manager;

    public function __construct($manager)
    {
        $this->manager      = $manager;
        $this->per_page     = $manager->per_page;
        $this->next_page    = $manager->next_page;
        $this->current_page = $manager->current_page;
    }

    public function next()
    {
        if ($this->position == 9 && isset($this->current_page) && !is_null($this->next_page)) {
            $this->position     = 0;
            $this->manager      = $this->manager->getResourceClass()::all($this->next_page);
            $this->per_page     = $this->manager->per_page;
            $this->next_page    = $this->manager->next_page;
            $this->current_page = $this->manager->current_page;
        } else {
            $this->position++;
        }
    }

    public function valid()
    {
        return isset($this->manager->resources[$this->position]);
    }

    public function current()
    {
        return $this->manager->resources[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    // Implements ArrayAccess
    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->manager->resources[] = $value;
        } else {
            $this->manager->resources[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->manager->resources[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->manager->resources[$offset]);
    }

    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? $this->manager->resources[$offset] : null;
    }
}