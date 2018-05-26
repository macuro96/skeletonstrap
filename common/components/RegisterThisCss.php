<?php

namespace common\components;

use yii\web\View;

/**
 * Componente común que registra automáticamente una clase css teniendo en cuenta
 * que este ubicada en una ruta formada de la siguiente forma:
 * -> css/nombre-controlador/nombre-accion/nombre-archivo.css
 */
class RegisterThisCss extends RegisterThis
{
    /**
     * Registra de forma automática el estilo de una vista de una acción de un controlador
     * en concreto. Siguiendo una ubicación determinada para el archivo:
     * -> css/nombre-controlador/nombre-accion/nombre-archivo.css
     * @param  View  $view  Vista con la que obtener el controlador y la acción
     * @return bool         Si se ha realizado correctamente o no el registro en la vista.
     */
    public static function register($view)
    {
        $ruta = parent::rutaExtension($view, 'css');

        return $view->registerCssFile($ruta);
    }
}
