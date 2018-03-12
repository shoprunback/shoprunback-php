<?php

namespace Shoprunback\Resources;

class User extends Resource
{
    public function display()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}