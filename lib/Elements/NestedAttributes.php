<?php

namespace Shoprunback\Elements;

interface NestedAttributes
{
    static function getBelongsTo();
    static function getAcceptNestedAttributes();
    static function canOnlyBeNested();
}
