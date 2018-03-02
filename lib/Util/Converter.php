<?php

namespace Shoprunback\Util;

abstract class Converter
{
    const RESSOURCES_NAMESPACE = 'Shoprunback\Resources\\';

    public static function convertToSRBObject($baseObject, $targetObject)
    {
        $object = new $targetObject();

        foreach ($baseObject as $key => $value) {
            $object->$key = self::getContent($key, $value);
        }

        return $object;
    }

    public static function searchSRBObjectsInArrayOrObject($container)
    {
        // If it is neither an array nor an object, we simply return the value
        if (!is_array($container) && !is_object($container)) {
            return $container;
        }

        // We create the appropriate var
        $checked = !is_array($container) ? new \StdClass() : [];

        foreach ($container as $key => $value) {
            // We add the value to the container with the according syntax
            if (is_array($checked)) {
                $checked[$key] = self::getContent($key, $value);
            } else {
                $checked->$key = self::getContent($key, $value);
            }
        }

        return $checked;
    }

    private static function getContent($key, $value)
    {
        // We get the key that may be a class name
        $keyAsClassName = ucfirst($key);
        // We check if the object is an array of objects we have a class for (ex: items)
        $trimedKeyClassName = rtrim($keyAsClassName, 's');
        // The value we will return
        $valueToAdd;

        // If the trimed string is a class but not the not-trimed, it means it is an array of objects we have a class for
        if (class_exists(self::RESSOURCES_NAMESPACE . $trimedKeyClassName) && !class_exists(self::RESSOURCES_NAMESPACE . $keyAsClassName)) {
            $valueToAdd = [];

            // Since it is an array only of objects we have a class for, we call the recursive function to convert each object
            foreach ($value as $k => $v) {
                $valueToAdd[] = self::convertToSRBObject($v, self::RESSOURCES_NAMESPACE . $trimedKeyClassName);
            }
        } else {
            // If we have a class for the object, we convert it. Or else, we go to the normal recursive function
            $valueToAdd = class_exists(self::RESSOURCES_NAMESPACE . $keyAsClassName) ? self::convertToSRBObject($value, self::RESSOURCES_NAMESPACE . $keyAsClassName) : self::searchSRBObjectsInArrayOrObject($value);
        }

        return $valueToAdd;
    }
}