<?php

namespace Shoprunback\Util;

define('LOG_PATH', dirname(__FILE__, 3) . '/logs/');

class Logger
{
    const INFO = 0;
    const ERROR = 1;

    static private function log($message = '', $logType = self::INFO)
    {
        $filePath = LOG_PATH . date('Y-m-d') . '.txt';

        // Create file if doesn't exist
        if (!file_exists($filePath)) {
            fopen($filePath, 'w');
            chmod($filePath, 0777);
        }

        error_log(date('[Y-m-d H:i:s]: ') . $message . "\n", 3, $filePath);
    }

    static public function info($message = '')
    {
        self::log($message, self::INFO);
    }

    static public function error($message = '')
    {
        self::log($message, self::ERROR);
    }
}