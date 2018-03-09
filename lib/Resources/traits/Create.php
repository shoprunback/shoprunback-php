<?php

namespace Shoprunback\Resources;

trait Create
{
    public static function create($resource)
    {
        $resource->save();
        return $resource;
    }
}
