<?php

namespace Shoprunback\Elements;

class Order extends Element
{
    public function __toString()
    {
        return $this->display($this->order_number);
    }
}