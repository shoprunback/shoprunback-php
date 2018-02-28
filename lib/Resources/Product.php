<?php

namespace Shoprunback\Resources;

class Product extends ApiObject
{
    public $label;
    public $reference;
    public $ean;
    public $weight_grams;
    public $width_mm;
    public $length_mm;
    public $height_mm;
    public $picture_file_base64;
    public $picture_file_url;
    public $created_at;
    public $updated_at;

    public $brand_id;
    public $brand;

    static public function getApiUrlResource()
    {
        return 'products';
    }

    public function display()
    {
        return $this->label;
    }
}