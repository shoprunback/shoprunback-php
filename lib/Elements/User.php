<?php

namespace Shoprunback\Elements;

class User extends Element
{
    public function __toString()
    {
        return $this->display($this->first_name . ' ' . $this->last_name);
    }
}