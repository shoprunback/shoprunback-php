<?php

namespace Shoprunback\Elements;

class SparePart extends Element
{
    use Retrieve;
    use All;
    use Update;
    use Create;
    use Delete;

    public function __toString()
    {
        return $this->display($this->name);
    }

    public function getAllAttributes()
    {
        return get_object_vars($this);
    }

    public function getApiAttributesKeys()
    {
        return [
            'id',
            'reference',
            'name',
            'description',
            'picture_url',
            'metadata',
        ];
    }

    public static function getBaseEndpoint()
    {
        return "spare_parts";
    }

    public static function canGetAll()
    {
        return false;
    }

    public static function canOnlyBeNested()
    {
        return true;
    }

    public static function getBelongsTo()
    {
        return ['product'];
    }
}
