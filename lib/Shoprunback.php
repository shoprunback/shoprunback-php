<?php

namespace Shoprunback;

use Shoprunback\Util\Logger;
use Shoprunback\Resources\User;

class Shoprunback
{
    const VERSION = '0.1.0';

    public static function isTesting()
    {
        return defined('TESTING');
    }
}
