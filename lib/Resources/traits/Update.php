<?php

namespace Shoprunback\Resources;

trait Update
{
    public static function update($resource)
    {
        $resource->put();
        return $resource;
    }
}
