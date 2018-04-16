<?php

namespace Shoprunback\Elements;

class Order extends Element
{
    use Retrieve;
    use All;
    use Create;
    use Delete;

    private $shipback;
    private $customer;

    public function __toString()
    {
        return $this->display($this->order_number);
    }

    public static function getBelongsTo()
    {
        return [];
    }

    public static function getAcceptNestedAttributes()
    {
        return ['items', 'customer'];
    }

    public static function canOnlyBeNested()
    {
        return false;
    }

    public function getAllAttributes()
    {
        return get_object_vars($this);
    }

    public function setShipback($shipback)
    {
        $this->shipback = $shipback;
    }

    public function getShipback()
    {
        return $this->shipback;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    public function getCustomer()
    {
        return $this->customer;
    }
}