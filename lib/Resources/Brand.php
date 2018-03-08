<?php

namespace Shoprunback\Resources;

class Brand extends Resource
{
    use Retrieve;
    use All;

    public function display()
    {
        return $this->name;
    }
}