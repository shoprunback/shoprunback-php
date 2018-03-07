<?php

namespace Shoprunback\Resources;

use Shoprunback\Shoprunback;
use Shoprunback\ApiCaller;
use Shoprunback\Util\Converter;
use Shoprunback\Error\UnknownApiToken;

abstract class ApiObject extends Resource
{
    abstract public static function getApiUrlResource();

    abstract public static function getApiUrlReference();

    abstract public function display();

    private static function getCaller()
    {
        return 'Shoprunback\ApiCaller';
    }

    public static function fetch($id = '')
    {
        if (!Shoprunback::isSetup()) throw new UnknownApiToken('Can\'t fetch if not setup');

        $item = self::convertToSelf(self::getCaller()::get(static::getApiUrlResource(), $id));

        static::logCurrentClass('"' . $item->display() . '" fetched');

        return $item;
    }

    public function save($noId = false)
    {
        if (!Shoprunback::isSetup()) throw new UnknownApiToken('Can\'t save item if not setup');

        try {
            $callResult = ApiCaller::save(static::getApiUrlResource(), $this, $noId);
        } catch (Error $error) {
            return false;
        }

        static::logCurrentClass('"' . $this->display() . '" saved');

        if (!isset($this->id)) {
            $this->id = $callResult->id;
        }
    }

    public static function convertToSelf($object)
    {
        return Converter::convertToSRBObject($object, get_called_class());
    }
}