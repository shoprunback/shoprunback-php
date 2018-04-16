<?php

namespace Shoprunback\Elements;

class Product extends Element
{
    use Retrieve;
    use All;
    use Update;
    use Create;
    use Delete;

    public function __toString()
    {
        return $this->display($this->label);
    }

    public static function getBelongsTo()
    {
        return ['brand'];
    }

    public static function getAcceptNestedAttributes()
    {
        return ['brand'];
    }
}