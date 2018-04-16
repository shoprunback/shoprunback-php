<?php

namespace Shoprunback\Elements;

class Shipback extends Element
{
    use Retrieve;
    use All;
    use Create;
    use Update;
    use Delete;

    private $order;
    private $customer;

    public function __toString()
    {
        return $this->display($this->id);
    }

    public static function getBelongsTo()
    {
        return ['order'];
    }

    public static function getAcceptNestedAttributes()
    {
        return ['returnedItems', 'customer'];
    }

    public static function canOnlyBeNested()
    {
        return false;
    }

    public function getAllAttributes()
    {
        return get_object_vars($this);
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    public function getOrder()
    {
        return $this->order;
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