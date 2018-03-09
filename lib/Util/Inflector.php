<?php

namespace Shoprunback\Util;

abstract class Inflector
{
    const RESSOURCES_NAMESPACE = 'Shoprunback\Resources\\';

    public static function constantize($mixed, $inflectedClassName)
    {
        $inflectedClassName = self::RESSOURCES_NAMESPACE . str_replace(self::RESSOURCES_NAMESPACE, '', $inflectedClassName);
        $object = new $inflectedClassName();

        foreach ($mixed as $key => $value) {
            $object->$key = self::getContent($key, $value);
        }

        return $object;
    }

    private static function addValueToMixed(&$mixed, $key, $value)
    {
        if (is_array($mixed)) {
            $mixed[$key] = $value;
        } else {
            $mixed->$key = $value;
        }
    }

    private static function isContainer($object)
    {
        return is_array($object) || is_object($object);
    }

    private static function inflectContainer($container)
    {
        $inflectedContainer = !is_array($container) ? new \StdClass() : [];

        foreach ($container as $key => $value) {
            self::addValueToMixed($inflectedContainer, $key, self::getContent($key, $value));
        }

        return $inflectedContainer;
    }

    private static function searchSRBObjectsInArrayOrObject($container) #TODO rename
    {
        if (self::isContainer($container)) {
            return self::inflectContainer($container);
        }

        return $container;
    }

    public static function classify($string)
    {
        if (substr($string, -3) == 'ies') {
            return rtrim(ucfirst($string), 'ies') . 'y';
        } else {
            return rtrim(ucfirst($string), 's');
        }
    }

    public static function isPluralClassName($className, $string) {
        return self::pluralize($className) == $string;
    }

    public static function pluralize($className)
    {
        if (substr($className, -1) == 'y') {
            return strtolower(rtrim($className, 'y') . 'ies');
        } else {
            return strtolower($className . 's');
        }
    }

    public static function isKnownResource($className) {
        return class_exists(self::RESSOURCES_NAMESPACE . $className);
    }

    private static function getContent($key, $value)
    {
        $className = self::classify($key);

        $valueToAdd = [];

        if (self::isKnownResource($className) && self::isPluralClassName($className, $key)) {
            foreach ($value as $k => $v) {
                $valueToAdd[] = self::constantize($v, $className);
            }
        } else {
            $valueToAdd = self::isKnownResource($className)
                ? self::constantize($value, $className)
                : self::searchSRBObjectsInArrayOrObject($value);
        }

        return $valueToAdd;
    }
}