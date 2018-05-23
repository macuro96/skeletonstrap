<?php

namespace common\components;

use yii\web\View;

use yii\helpers\Url;

/**
 * Componente común: abstracción de registro de archivos
 */
abstract class RegisterThis
{
    /**
     * Devuelve una ruta con el nombre del controlador utilizado, su acción
     * y la extensión del archivo a registrar.
     * @param  View   $view      Vista con la que obtener el controlador y la acción
     * @param  string $extension Extensión del archivo a registrar.
     * @return string            Ruta creada.
     */
    protected static function rutaExtension($view, string $extension)
    {
        $controlador = $view->context->module->controller;

        $nombreControlador = $controlador->id;
        $nombreAccion      = $controlador->action->id;

        $ruta = $extension . '/' . $nombreControlador . '/' . $nombreAccion . '.' . $extension;

        return Url::to([$ruta]);
    }
}
