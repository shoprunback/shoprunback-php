<?php

namespace Shoprunback\Resources;

trait Create
{
    public static function create($resource)
    {
        $resource->post();
        return $resource;
    }
}
