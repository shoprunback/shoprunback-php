<?php

namespace Shoprunback\Resources;

use Shoprunback\Shoprunback;
use Shoprunback\ApiCaller;
use Shoprunback\Util\Converter;
use Shoprunback\Error\UnknownApiToken;
use Shoprunback\RestClient;

abstract class ShoprunbackObject extends Resource
{
    public function refresh()
    {
        $restClient = RestClient::getClient();
        $response = $restClient->request($this->endpoint(), \Shoprunback\RestClient::GET);
        $this->convertResponseToSelf($response);
    }

    public static function convertObjectToSelf($object)
    {
        return Converter::convertToSRBObject($object, get_called_class());
    }

    public function convertResponseToSelf($response)
    {
        $result = self::convertObjectToSelf($response->getBody());

        foreach ($result as $key => $value) {
            $this->$key = $value;
        }
    }









    // abstract public static function getApiUrlResource();

    // abstract public static function getApiUrlReference();

    // abstract public function display();

    // private static function getCaller()
    // {
    //     return 'Shoprunback\ApiCaller';
    // }

    // public static function fetch($id = '')
    // {

    //     $item = self::convertToSelf(self::getCaller()::get(static::getApiUrlResource(), $id));

    //     static::logCurrentClass('"' . $item->display() . '" fetched');

    //     return $item;
    // }

    // public function save($noId = false)
    // {
    //     if (!Shoprunback::isSetup()) throw new UnknownApiToken('Can\'t save item if not setup');

    //     try {
    //         $callResult = ApiCaller::save(static::getApiUrlResource(), $this, $noId);
    //     } catch (Error $error) {
    //         return false;
    //     }

    //     static::logCurrentClass('"' . $this->display() . '" saved');

    //     if (!isset($this->id)) {
    //         $this->id = $callResult->id;
    //     }
    // }
}