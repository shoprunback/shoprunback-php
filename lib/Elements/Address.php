<?php

namespace Shoprunback\Elements;

class Address extends Element
{
    public function __toString()
    {
        return $this->display($this->line1 . ' ' . $this->line2 . ', ' . $this->city . ' ' . $this->zipcode . ', ' . $this->country_code);
    }

    public static function getBelongsTo()
    {
        return ['customer'];
    }

    public static function getAcceptNestedAttributes()
    {
        return [];
    }

    public static function canOnlyBeNested()
    {
        return true;
    }
}