<?php

namespace Shoprunback\Elements;

class Product extends Element
{
    use Retrieve;
    use All;
    use Update;
    use Create;
    use Delete;

    private $brand;
    private $spare_parts;

    public function __toString()
    {
        return $this->display($this->label);
    }

    public static function setAttributesFromMixedInElement($mixed)
    {
        $element = parent::setAttributesFromMixedInElement($mixed);

        // To have both picture_file_url and picture_url
        if (isset($element->picture_file_url) && !is_null($element->picture_file_url) && (!isset($element->picture_url) || is_null($element->picture_url))) {
            $element->picture_url = $element->picture_file_url;
        } elseif (isset($element->picture_url) && !is_null($element->picture_url) && (!isset($element->picture_file_url) || is_null($element->picture_file_url))) {
            $element->picture_file_url = $element->picture_url;
        }

        return $element;
    }

    public static function getBelongsTo()
    {
        return ['brand'];
    }

    public static function getAcceptedNestedElements()
    {
        return ['brand','spare_parts'];
    }

    public function getAllAttributes()
    {
        return get_object_vars($this);
    }

    public function getApiAttributesKeys()
    {
        return [
            'id',
            'label',
            'reference',
            'ean',
            'weight_grams',
            'width_mm',
            'length_mm',
            'height_mm',
            'brand_id',
            'brand',
            'picture_file_base64',
            'picture_file_url',
            'created_at',
            'updated_at',
            'picture_url',
            'metadata',
            'spare_parts'
        ];
    }

    public function deleteImage()
    {
        self::deleteImageCall($this->id);
        $this->refresh();
    }

    public static function deleteImageCall($productId)
    {
        \Shoprunback\RestClient::getClient()->request(self::deleteImageEndpoint($productId), \Shoprunback\RestClient::DELETE);
    }

    public static function deleteImageEndpoint($productId)
    {
        return self::deleteEndPoint($productId) . '/image';
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    public function getBrand()
    {
        return $this->brand;
    }
}
