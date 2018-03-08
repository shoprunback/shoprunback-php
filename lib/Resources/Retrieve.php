<?php

namespace Shoprunback\Resources;

trait Retrieve
{
    public function endpoint() {
        $class = get_called_class();
        $classPathArray = explode('\\', $class);
        return strtolower(end($classPathArray)) . 's/' . $this->id; #TODO pluralize this value
    }

    public static function retrieve($id)
    {
        $instance = new static($id);
        $instance->refresh();
        return $instance;
    }
}

?>