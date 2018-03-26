<?php

namespace Shoprunback\Elements;

class Brand extends Element
{
    use Retrieve;
    use All;
    use Update;
    use Create;
    use Delete;

    public function __toString()
    {
        return $this->display($this->name);
    }

    public static function getBelongsTo()
    {
        return [];
    }

    public static function getAcceptNestedAttributes()
    {
        return [];
    }

    public static function canOnlyBeNested()
    {
        return false;
    }

    public function getAllAttributes()
    {
        return get_object_vars($this);
    }
}