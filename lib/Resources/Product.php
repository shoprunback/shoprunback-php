<?php

namespace Shoprunback\Resources;

class Product extends Resource
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