<?php

namespace common\components;

use yii\web\View;

/**
 * Componente común que renderiza automáticamente un fichero de una vista común tanto
 * para frontend como para backend y teniendo en cuenta que este ubicada en una ruta formada de la siguiente forma:
 * -> common/views/nombre-controlador/nombre-accion/nombre-archivoView.php
 */
class RenderCommonView
{
    /**
     * Renderiza una vista de un archivo común tanto para frontend como para backend
     * ubicada de la siguiente forma:
     * -> common/views/nombre-controlador/nombre-accion/nombre-archivoView.php
     * @param  View   $view   Vista a donde renderizar el archivo común.
     * @param  array  $params parametros del renderizado de fichero (renderFile)
     * @return bool           Si se ha realizado correctamente el renderizado o no.
     */
    public static function render($view, array $params = [])
    {
        $controlador = $view->context->module->controller;

        $nombreControlador = $controlador->id;
        $nombreAccion      = ucfirst($controlador->action->id);

        $ruta = '@commonViews/' . $nombreControlador . '/' . $nombreAccion . 'View.php';

        return $view->renderFile($ruta, $params);
    }
}
