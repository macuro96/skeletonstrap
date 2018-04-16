<?php

namespace backend\components;

use yii\helpers\Url;

class Ruta
{
    public static function to($url)
    {
        return ('/admin/' . $url);
    }

    public static function urlToFrontEnd($url)
    {
        return str_replace('/admin', '', $url);
    }
}
