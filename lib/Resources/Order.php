<?php

namespace Shoprunback\Resources;

class Order extends ApiObject
{
    public $ordered_at;
    public $order_number;
    public $customer;
    public $created_at;

    public $shipback_id;
    public $items;

    static public function getApiUrlResource()
    {
        return 'orders';
    }

    public function display()
    {
        return $this->order_number;
    }
}