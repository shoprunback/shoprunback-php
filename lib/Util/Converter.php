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
            $trimedKeyClassName = rtrim($keyAsClassName, 's');

            if (class_exists(self::RESSOURCES_NAMESPACE . $trimedKeyClassName) && ! class_exists(self::RESSOURCES_NAMESPACE . $keyAsClassName)) {
                $valueToAdd = [];

                foreach ($value as $k => $v) {
                    $valueToAdd[] = self::convertToSRBObject($v, self::RESSOURCES_NAMESPACE . $trimedKeyClassName);
                }

                $object->$key = $valueToAdd;
            } else {
                $object->$key = class_exists(self::RESSOURCES_NAMESPACE . $keyAsClassName) ? self::convertToSRBObject($value, self::RESSOURCES_NAMESPACE . $keyAsClassName) : self::searchSRBObjectsInArrayOrObject($value);
            }
        }

        return $object;
    }

    private static function searchSRBObjectsInArrayOrObject($container)
    {
        if (! is_array($container) && ! is_object($container)) {
            return $container;
        }

        $checked = ! is_array($container) ? new \StdClass() : [];

        foreach ($container as $key => $value) {
            $keyAsClassName = ucfirst($key);
            $trimedKeyClassName = rtrim($keyAsClassName, 's');
            $valueToAdd = $value;

            if (class_exists(self::RESSOURCES_NAMESPACE . $keyAsClassName)) { // Check if value is a resource
                $valueToAdd = self::convertToSRBObject($value, self::RESSOURCES_NAMESPACE . $keyAsClassName);
            } else if (class_exists(self::RESSOURCES_NAMESPACE . $trimedKeyClassName)) { // Check if value is an array of resources
                $valueToAdd = [];

                foreach ($value as $k => $v) {
                    $valueToAdd[] = self::convertToSRBObject($v, self::RESSOURCES_NAMESPACE . $trimedKeyClassName);
                }
            } else if (is_array($value) || is_object($value)) { // Check if value is an array or an object
                $valueToAdd = self::searchSRBObjectsInArrayOrObject($value);
            }

            if (is_array($checked)) {
                $checked[$key] = $valueToAdd;
            } else {
                $checked->$key = $valueToAdd;
            }
        }

        return $checked;
    }
}