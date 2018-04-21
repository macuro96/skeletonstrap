<?php

namespace common\components;

class RegisterThisJs extends RegisterThis
{
    public static function register($view, $options = [])
    {
        $ruta = parent::rutaExtension($view, 'js');

        return $view->registerJsFile($ruta, $options);
    }
}
