<?php

namespace Shoprunback\Resources;

class Brand extends ApiObject
{
    public $name;
    public $reference;

    static public function getApiUrlResource()
    {
        return 'brands';
    }

    static public function getApiUrlReference()
    {
        return 'id';
    }

    public function display()
    {
        return $this->name;
    }
}