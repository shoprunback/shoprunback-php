<?php

namespace Shoprunback\Resources;

trait Delete
{
    public static function delete($id)
    {
        $instance = new static($id);
        return $instance->remove();
    }
}
