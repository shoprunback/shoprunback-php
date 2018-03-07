<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

class Mocker
{
    public static function request($endpoint, $type, $params = [])
    {
        if (!$endpoint || !$type) return false;

        switch ($type) {
            case 'GET':
                return self::getJson($endpoint, $params);
                break;

            default:
                return false;
                break;
        }
    }

    private static function getJson($endpoint, $params)
    {
        switch ($endpoint) {
            case 'brands':
                $decodedJson = json_decode(self::getBrandJson());

                if ((isset($params['id']) && $params['id'] == $decodedJson->id)
                    || (isset($params['reference']) && $params['reference'] == $decodedJson->reference)) {
                    return self::getBrandJson();
                }

                return false;
                break;

            default:
                return false;
                break;
        }
    }

    public static function getBrandJson()
    {
        return '{
            "id":"1234",
            "name":"Test Brand",
            "reference":"test-brand"
        }';
    }
}