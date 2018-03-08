<?php

namespace Shoprunback\Resources;

class Brand extends Resource
{
    use Retrieve;
    use All;
    use Update;

    public function display()
    {
        return $this->name;
    }
}