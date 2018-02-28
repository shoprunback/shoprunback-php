<?php

namespace Shoprunback\Resources;

class Item extends Resource
{
    public $label;
    public $reference;
    public $barcode;
    public $price_cents;
    public $currency;
    public $created_at;

    public $product_id;
    public $product;
}