<?php

namespace Shoprunback\Elements;

class Item extends Element
{
    public function __toString()
    {
        return $this->display($this->label);
    }

    public static function getBelongsTo()
    {
        return ['order'];
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