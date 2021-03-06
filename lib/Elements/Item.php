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

    public static function getAcceptedNestedElements()
    {
        return ['product'];
    }

    public static function canOnlyBeNested()
    {
        return true;
    }

    public function getAllAttributes()
    {
        return get_object_vars($this);
    }

    public function getApiAttributesKeys()
    {
        return [
            'id',
            'label',
            'reference',
            'barcode',
            'price_cents',
            'currency',
            'created_at',
            'tag_list',
            'product_id',
            'product'
        ];
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