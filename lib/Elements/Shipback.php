<?php

namespace Shoprunback\Elements;

class Shipback extends Element
{
    public function __toString()
    {
        return $this->display($this->id);
    }
}