<?php

namespace Shoprunback;

use Shoprunback\Error\ElementNumberDoesntExists;
use Shoprunback\ElementIterator;

class ElementManager extends \ArrayObject
{
    public function __construct($responseBody, $elementName)
    {
        $this->per_page     = 10;
        $this->next_page    = null;
        $this->current_page = 1;

        if (isset($responseBody->pagination)) {
            $this->count        = $responseBody->pagination->count;
            $this->per_page     = $responseBody->pagination->per_page;
            $this->next_page    = $responseBody->pagination->next_page;
            $this->current_page = $responseBody->pagination->current_page;

            $elementKey = $elementName::getAllElementKey();
            foreach ($responseBody->$elementKey as $element) {
                $this->elements[] = $elementName::newFromMixed($element);
            }
        } else {
            $this->count = count($responseBody);

            foreach ($responseBody as $element) {
                $this->elements[] = $elementName::newFromMixed($element);
            }
        }
    }

    public function count()
    {
        return count($this->elements);
    }

    public function getIterator()
    {
        return new ElementIterator($this);
    }

    public function offsetGet($id)
    {
        if (isset($this->elements[$id])) {
            return $this->elements[$id];
        }

        if ($id < $this->count) {
            return $this->getElementClass()::all(floor($id / $this->per_page))[$id % $this->per_page];
        }

        throw new ElementNumberDoesntExists('There is ' . $this->count . ' ' . $this->getElementClass()::getAllElementKey() . ' and the number asked was ' . $id);
    }

    public function getElementClass()
    {
        return get_class($this[0]);
    }

    public function getLast()
    {
        return $this[$this->count - 1];
    }
}