<?php

namespace common\components;

class RegisterThisCss extends RegisterThis
{
    public static function register($view)
    {
        $ruta = parent::rutaExtension($view, 'css');

        return $view->registerCssFile($ruta);
    }
}
