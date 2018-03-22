<?php

namespace Shoprunback\Elements;

trait Delete
{
    public static function delete($id)
    {
        $instance = new static($id);
        return $instance->remove();
    }
}
