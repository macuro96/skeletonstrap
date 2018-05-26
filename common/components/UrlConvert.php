<?php

namespace common\components;

/**
 * Componente común de conversión de url
 */
class UrlConvert
{
    /**
     * Convierte la url dada en backend ha una del frontend
     * @param  string $url Url de backend
     * @return string      Url convertida a frontend
     */
    public static function toFrontEnd(string $url)
    {
        return str_replace('/admin', '', $url);
    }
}
