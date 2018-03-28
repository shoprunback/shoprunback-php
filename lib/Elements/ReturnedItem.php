<?php

namespace Shoprunback\Elements;

class ReturnedItem extends Element
{
    private $item;

    public function __toString()
    {
        return $this->display($this->item->label);
    }

    public static function getBelongsTo()
    {
        return ['shipback'];
    }

    public static function getAcceptNestedAttributes()
    {
        return [];
    }

    public static function canOnlyBeNested()
    {
        return true;
    }

    public function getAllAttributes()
    {
        return get_object_vars($this);
    }

    public function setItem($item)
    {
        $this->item = $item;
    }

    public function getItem()
    {
        return $this->item;
    }
}