<?php

namespace Shoprunback\Resources;

class User extends Resource
{
    public function __toString()
    {
        return $this->display($this->first_name . ' ' . $this->last_name);
    }
}