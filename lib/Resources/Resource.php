<?php

namespace Shoprunback\Resources;

use Shoprunback\Util\Logger;
use Shoprunback\Shoprunback;

abstract class Resource
{
    public $id;

    protected static function logCurrentClass($message)
    {
        $calledClassNameExploded = explode('\\', get_called_class());
        Logger::info(end($calledClassNameExploded) . ' ' . $message);
    }
}