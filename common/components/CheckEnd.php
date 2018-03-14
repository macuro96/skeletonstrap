<?php

namespace common\components;

use yii\helpers\Url;

class CheckEnd
{
    public static function isFrontEnd()
    {
        return parse_url(Url::to('@web'))['path'] !== '/admin';
    }

    public static function isBackEnd()
    {
        return parse_url(Url::to('@web'))['path'] === '/admin';
    }

    public static function rutaRelativa()
    {
        return self::isBackEnd() ? '../' : '/';
    }
}
