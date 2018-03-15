<?php

namespace Shoprunback\Resources;

class Shipback extends Resource
{
    public function __toString()
    {
        return $this->display($this->id);
    }
}