<?php

namespace Shoprunback\Resources;

class Shipback extends ApiObject
{
    public $rma;
    public $weight_in_grams;
    public $computed_weight_in_grams;
    public $created_at;
    public $public_url;
    public $size;
    public $quotes;

    public $returned_items;
    public $order_id;
    public $order;
    public $company_id;
    public $company;
    public $customer;

    static public function getApiUrlResource()
    {
        return 'shipbacks';
    }

    public function display()
    {
        return $this->id;
    }
}