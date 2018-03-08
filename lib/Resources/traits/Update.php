<?php

namespace Shoprunback\Resources;

trait Update
{
    public static function update($resource)
    {
        $instance = new static($resource->id);
        $instance->put($resource);
        return $instance;
    }
}
