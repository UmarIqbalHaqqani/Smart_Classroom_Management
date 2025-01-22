<?php

namespace App\Enums;

class MenuType
{
    const IFRAME    = 'iframe';
    const URL       = 'url';
    const CONTENT   = 'content';

    public static function getValues()
    {
        return [
            self::IFRAME,
            self::URL,
            self::CONTENT,
        ];
    }
}
