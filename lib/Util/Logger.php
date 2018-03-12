<?php

namespace Shoprunback\Util;

define('LOG_PATH', dirname(__FILE__, 3) . '/logs/');

class Logger
{
    const FILENAME_DATE_FORMAT = 'Y-m-d';
    const LINE_PREFIX_DATE_FORMAT = '[Y-m-d H:i:s]: ';
    const FILE_EXTENSION = '.txt';

    const INFO = 0;
    const ERROR = 1;

    static private function log($message = '', $logType = self::INFO)
    {
        $filePath = LOG_PATH . date(self::FILENAME_DATE_FORMAT) . self::FILE_EXTENSION;

        // Create file if doesn't exist
        if (!file_exists($filePath)) {
            fopen($filePath, 'w');
            chmod($filePath, 0777);
        }

        error_log(date(self::LINE_PREFIX_DATE_FORMAT) . $message . "\n", 3, $filePath);
    }

    static public function info($message = '')
    {
        self::log($message, self::INFO);
    }

    static public function error($message = '')
    {
        self::log($message, self::ERROR);
    }

    static public function getLastLine($dateToFormat = '')
    {
        $date = $dateToFormat ? date($dateToFormat, self::FILENAME_DATE_FORMAT) : date(self::FILENAME_DATE_FORMAT);
        $allLines = explode("\n", file_get_contents(LOG_PATH . $date . self::FILE_EXTENSION));
        return $allLines[count($allLines) - 2];
    }
}