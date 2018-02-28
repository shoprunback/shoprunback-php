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

    public function display()
    {
        return $this->name;
    }
}