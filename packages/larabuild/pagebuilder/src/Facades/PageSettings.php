<?php

namespace Larabuild\Pagebuilder\Facades;

use Illuminate\Support\Facades\Facade;

class PageSettings extends Facade {
    protected static function getFacadeAccessor() {
        return 'page_settings';
    }
}
