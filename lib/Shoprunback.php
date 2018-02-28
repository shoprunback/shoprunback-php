<?php

namespace Shoprunback;

use Shoprunback\Util\Logger;
use Shoprunback\Resources\User;

class Shoprunback
{
    const VERSION = '1.0.0';

    public static $apiBaseUrl = 'https://dashboard.shoprunback.com/api/v1/';
    public static $apiToken = '';

    public static function getApiBaseUrl()
    {
        return self::$apiBaseUrl;
    }

    public static function setApiBaseUrl($apiUrl)
    {
        self::$apiBaseUrl = $apiUrl;
        Logger::info('NEW API URL SET: "' . $apiUrl . '"');
    }

    public static function getApiToken()
    {
        return self::$apiToken;
    }

    public static function setApiToken($token)
    {
        $oldApiToken = self::getApiToken();
        self::$apiToken = $token;

        try {
            User::fetch();
        } catch (Error $e) {
            self::$apiToken = $oldApiToken;
        }

        Logger::info('USER API TOKEN SET: "' . substr($token, 0, 3) . '...' . substr($token, -3) . '"');
    }

    public static function isSetup()
    {
        return self::$apiToken !== '';
    }
}
