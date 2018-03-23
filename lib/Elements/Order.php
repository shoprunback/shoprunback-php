<?php

namespace Shoprunback\Elements;

class Order extends Element
{
    use Retrieve;
    use All;
    use Create;

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
}