<?php

namespace Shoprunback\Elements;

class Item extends Element
{
    private $product;

    public function __toString()
    {
        return $this->display($this->label);
    }

    public static function getBelongsTo()
    {
        return ['order'];
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

    public function setProduct($product)
    {
        $this->product = $product;
    }

    public function getProduct()
    {
        return $this->product;
    }
}