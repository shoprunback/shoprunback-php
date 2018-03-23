<?php

namespace Shoprunback\Elements;

class Customer extends Element
{
    public function __toString()
    {
        return $this->display($this->first_name . ' ' . $this->last_name);
    }

    public static function getBelongsTo()
    {
        return ['order'];
    }

    public static function getAcceptNestedAttributes()
    {
        return ['address'];
    }

    public static function canOnlyBeNested()
    {
        return true;
    }
}