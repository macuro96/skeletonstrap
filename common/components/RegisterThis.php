<?php

namespace common\components;

use yii\helpers\Url;

abstract class RegisterThis
{
    protected static function rutaExtension($view, $extension)
    {
        $controlador = $view->context->module->controller;

        $nombreControlador = $controlador->id;
        $nombreAccion      = $controlador->action->id;

        $ruta = $extension . '/' . $nombreControlador . '/' . $nombreAccion . '.' . $extension;

        return Url::to([$ruta]);
    }
}
