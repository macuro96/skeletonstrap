<?php

namespace common\components;

use yii\web\View;

/**
 * Componente común que registra automáticamente una script js teniendo en cuenta
 * que este ubicada en una ruta formada de la siguiente forma:
 * -> js/nombre-controlador/nombre-accion/nombre-archivo.js
 */
class RegisterThisJs extends RegisterThis
{
    /**
     * Registra de forma automática el script js de una vista de una acción de un controlador
     * en concreto. Siguiendo una ubicación determinada para el archivo:
     * -> js/nombre-controlador/nombre-accion/nombre-archivo.js
     * @param  View  $view    Vista con la que obtener el controlador y la acción
     * @param  array $options Array de opciones para el registro del archivo js. Por defecto está vacío.
     * @return bool           Si se ha realizado correctamente o no el registro en la vista.
     */
    public static function register($view, array $options = [])
    {
        $ruta = parent::rutaExtension($view, 'js');

        return $view->registerJsFile($ruta, $options);
    }
}
