<?php

namespace Shoprunback\Resources;

class Brand extends Resource
{
    use Retrieve;
    use All;
    use Update;
    use Create;

    public function display()
    {
        return $this->name;
    }
}