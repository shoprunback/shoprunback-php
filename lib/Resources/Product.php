<?php

namespace Shoprunback\Resources;

class Product extends Resource
{
    use Retrieve;
    use All;
    use Update;
    use Create;
    use Delete;

    public function display()
    {
        return $this->label;
    }
}