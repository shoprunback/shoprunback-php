<?php

namespace Shoprunback\Util;

abstract class Converter
{
    const RESSOURCES_NAMESPACE = 'Shoprunback\Resources\\';

    public static function convertToSRBObject($baseObject, $targetObject)
    {
        $object = new $targetObject();

        foreach ($baseObject as $key => $value) {
            $keyAsClassName = ucfirst($key);
            $trimedClassName = rtrim($keyAsClassName, 's');

            // If the value is an array of objects, we convert them one by one recursively
            if (is_array($value) && class_exists(self::RESSOURCES_NAMESPACE . $trimedClassName)) {
                $object->$key = [];

                foreach ($value as $k => $v) {
                    $object->$key[] = self::convertToSRBObject($v, self::RESSOURCES_NAMESPACE . $trimedClassName);
                }
            } else {
                // If the key is a class, the attribute becomes an instance of this class. Else, we simply put the value in the object
                $object->$key = class_exists(self::RESSOURCES_NAMESPACE . $keyAsClassName) ? self::convertToSRBObject($value, self::RESSOURCES_NAMESPACE . $keyAsClassName) : $value;
            }
        }

        return $object;
    }
}