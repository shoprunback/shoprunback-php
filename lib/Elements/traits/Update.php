<?php

namespace Shoprunback\Elements;

trait Update
{
    public static function update($element)
    {
        $element->put();
        return $element;
    }
}
