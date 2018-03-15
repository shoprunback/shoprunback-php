<?php

namespace Shoprunback\Resources;

class Order extends Resource
{
    public function __toString()
    {
        return $this->display($this->order_number);
    }
}