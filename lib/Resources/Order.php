<?php

namespace Shoprunback\Resources;

class Order extends Resource
{
    public function display()
    {
        return $this->order_number;
    }
}