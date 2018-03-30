<?php

namespace Shoprunback\Elements;

class Warehouse extends Element
{
    use Retrieve;
    use All;
    use Create;

    private $address;

    public function __toString()
    {
        return $this->display($this->name);
    }

    public static function getBelongsTo()
    {
        return [];
    }

    public static function getAcceptNestedAttributes()
    {
        return ['address'];
    }

    public static function canOnlyBeNested()
    {
        return false;
    }

    public function getAllAttributes()
    {
        return get_object_vars($this);
    }

    public function setAddress($address)
    {
        $this->address = $address;
    }

    public function getAddress()
    {
        return $this->address;
    }
}