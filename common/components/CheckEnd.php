<?php

namespace common\components;

use yii\helpers\Url;

/**
 * Componente común:
 * Funciones de comprobación relacionadas con el backend y frontend.
 */
class CheckEnd
{
    /**
     * Comprueba si esta en el frontend actualmente
     * @return bool
     */
    public static function isFrontEnd()
    {
        return parse_url(Url::to('@web'))['path'] !== '/admin';
    }

    /**
     * Comprueba si esta en el backend actualmente
     * @return bool
     */
    public static function isBackEnd()
    {
        return parse_url(Url::to('@web'))['path'] === '/admin';
    }

    /**
     * Devuelve la ruta relativa con respecto a la raiz dependiendo si
     * está en backend o frontend.
     * @return string
     */
    public static function rutaRelativa()
    {
        return self::isBackEnd() ? '../../' : '/';
    }
}
