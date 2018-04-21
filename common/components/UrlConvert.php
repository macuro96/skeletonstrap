<?php

namespace common\components;

class UrlConvert
{
    public static function toFrontEnd($url)
    {
        return str_replace('/admin', '', $url);
    }
}
