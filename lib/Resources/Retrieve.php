<?php

namespace Shoprunback\Resources;

trait Retrieve
{

    public static function endpoint() {
        return strtolower(get_called_class()) . 's'; #TODO pluralize this value
    }

    public static function retrieve($id)
    {
        echo self::endpoint();

        $instance = new static($id);
        $instance->refresh();
        return $instance;
    }
}

?>