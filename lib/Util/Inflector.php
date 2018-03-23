<?php

namespace Shoprunback\Util;

use Shoprunback\Util\Container;

abstract class Inflector
{
    const ELEMENTS_NAMESPACE = 'Shoprunback\Elements\\';

    public static function classify($string)
    {
        if (substr($string, -3) == 'ies') {
            return rtrim(ucfirst($string), 'ies') . 'y';
        } elseif (substr($string, -2) != 'ss') {
            return rtrim(ucfirst($string), 's');
        }

        return $string;
    }

    public static function pluralize($className)
    {
        if (substr($className, -1) == 'y') {
            return strtolower(rtrim($className, 'y') . 'ies');
        } else if (substr($className, -2) == 'ss') {
            return strtolower($className . 'es');
        } else if (substr($className, -1) == 's') {
            return strtolower($className);
        } else {
            return strtolower($className . 's');
        }
    }

    public static function isPluralClassName($className, $string) {
        return self::pluralize($className) == $string;
    }

    public static function isKnownElement($className) {
        return class_exists(self::ELEMENTS_NAMESPACE . $className);
    }

    public static function constantize($mixed, $inflectedClassName)
    {
        $inflectedClassName = self::ELEMENTS_NAMESPACE . str_replace(self::ELEMENTS_NAMESPACE, '', $inflectedClassName);
        $object = new $inflectedClassName();

        foreach ($mixed as $key => $value) {
            $object->$key = self::getContent($key, $value);
        }

        return $object;
    }

    private static function inflectContainer($container)
    {
        $inflectedContainer = !is_array($container) ? new \StdClass() : [];

        foreach ($container as $key => $value) {
            Container::addValueToContainer($inflectedContainer, $key, self::getContent($key, $value));
        }

        return $inflectedContainer;
    }

    private static function searchElementInContainer($container)
    {
        if (Container::isContainer($container)) {
            return self::inflectContainer($container);
        }

        return $container;
    }

    private static function getContent($key, $value)
    {
        $className = self::classify($key);

        $valueToAdd = [];

        if (self::isKnownElement($className) && self::isPluralClassName($className, $key)) {
            foreach ($value as $k => $v) {
                $valueToAdd[] = self::constantize($v, $className);
            }
        } else {
            $valueToAdd = self::isKnownElement($className)
                ? self::constantize($value, $className)
                : self::searchElementInContainer($value);
        }

        return $valueToAdd;
    }
}