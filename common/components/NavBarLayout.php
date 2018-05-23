<?php

namespace common\components;

/**
 * Componente común de barra de navegación de layout main
 */
class NavBarLayout
{
    /**
     * Brand label
     * @return string Devuelve el nombre de la aplicación
     */
    public static function brandLabel()
    {
        return \Yii::$app->name;
    }

    /**
     * Boton de login
     * @return string
     */
    public static function loginButton()
    {
        return  [
                    'label' => 'Login' . ' <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>',
                    'url' => ['/site/login']
                ];
    }
}
