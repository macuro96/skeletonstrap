<?php

namespace common\components;

class RenderCommonView
{
    public static function render($view, array $params = [])
    {
        $controlador = $view->context->module->controller;

        $nombreControlador = $controlador->id;
        $nombreAccion      = ucfirst($controlador->action->id);

        $ruta = '@commonViews/' . $nombreControlador . '/' . $nombreAccion . 'View.php';

        return $view->renderFile($ruta, $params);
    }
}
