<?php

namespace common\components;

use yii\helpers\Html;

/**
 * Componente común relacionado con los recursos.
 */
class Recursos
{
    /**
     * Devuelve una imagen común guardada en images/web teniendo en cuenta si está en frontend o backend.
     * @param  string     $nombre  nombre de la imagen (junto con su extensión)
     * @param  array|null $options opciones de configuración para la imagen, tiene img-responsive por defecto.
     * @return string          html
     */
    public static function imageCommon(string $nombre, ?array $options = ['class' => 'img-responsive'])
    {
        return Html::img(CheckEnd::rutaRelativa() . 'images/' . $nombre, $options);
    }

    /**
     * Devuelve una imagen guardada en su images/web correspondiente (frontend o backend).
     * @param  string     $nombre  nombre de la imagen (junto con su extensión)
     * @param  array|null $options opciones de configuración para la imagen, tiene img-responsive por defecto.
     * @return string          html
     */
    public static function image(string $nombre, ?array $options = ['class' => 'img-responsive'])
    {
        $lugar = CheckEnd::isBackEnd() ? '/admin/' : '';

        return Html::img($lugar . 'images/' . $nombre, $options);
    }
}
