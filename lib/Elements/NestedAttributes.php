<?php

namespace Shoprunback\Elements;

interface NestedAttributes
{
    static function getBaseEndpoint();
    static function getBelongsTo();
    static function getAcceptedNestedElements();
    static function canOnlyBeNested();
}
