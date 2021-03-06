<?php

namespace Shoprunback\Elements;

use Shoprunback\Error\ElementCannotBeCreated;

class Product extends Element
{
    use Retrieve;
    use All;
    use Update;
    use Create;
    use Delete;

    private $brand;

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

    //Override save
    public function save()
    {
        $this->loadOriginal();
        $body = $this->getElementBody(false);
        $spareParts = [];

        if (isset($body->spare_parts)) {
            $spareParts = $body->spare_parts;
        }
        if ($this->isPersisted()) {
            if (static::canUpdate()) {
                $this->put();
                $this->addSparePartToProduct($spareParts);
            } else {
                $this->refresh();
            }
        } else {
            if (static::canCreate()) {
                $this->post();
                $this->addSparePartToProduct($spareParts);
            } else {
                throw new ElementCannotBeCreated(Inflector::tryToGetClass($this) . ' cannot be created');
            }
        }
    }

    public function addSparePartToProduct($spareParts)
    {
        $productId = $this->id;
        if (is_array($spareParts) && !empty($spareParts)) {
            self::createSparePart($productId, $spareParts);
        }
        $this->setSpareParts($this->getSpareParts()->spare_parts);
    }

    public function createSparePart($productId, $spareParts)
    {
        $body = ["bulk" => $spareParts];
        \Shoprunback\RestClient::getClient()->request(self::createSparePartEndpoint($productId), 'POST', $body);
    }

    public function createSparePartEndpoint($productId)
    {
        return $this->getBaseEndpoint()."/".$productId."/parts/bulk";
    }

    public function getSpareParts()
    {
        $productId = $this->id;
        $res = \Shoprunback\RestClient::getClient()->request($this->getBaseEndpoint()."/".$productId."/parts", \Shoprunback\RestClient::GET);
        return $res->getBody();
    }

    public function removeSpareParts($sparePartId)
    {
        $productId = $this->id;
        return \Shoprunback\RestClient::getClient()->request($this->getBaseEndpoint()."/".$productId."/parts/".$sparePartId, \Shoprunback\RestClient::DELETE);
    }

    public function setSpareParts($spare_parts)
    {
        $this->spare_parts = $spare_parts;
    }

}
