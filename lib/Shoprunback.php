<?php

namespace Shoprunback;

use Shoprunback\Util\Logger;
use Shoprunback\Elements\User;

class Shoprunback
{
    const VERSION = '0.1.1';

    public static function isTesting()
    {
        return defined('TESTING');
    }
}
