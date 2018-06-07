<?php

namespace common\components;

use common\models\Config;

/**
 * Componente común para funciones de twitch.
 */
class Twitch
{
    /**
     * Crea el iframe del vídeo en directo del canal de skeletonstraptv
     */
    public static function directo()
    {
        ?>
        <iframe class="twitch-video"
        src="http://player.twitch.tv/?channel=<?= Config::find()->one()->usuario_twitch ?>"
        height="720"
        width="80%"
        frameborder="0"
        scrolling="no"
        allowfullscreen="true">
        </iframe>
        <?php
    }

    /**
     * Crea el iframe de una colección de vídeos del canal skeletonstraptv
     * @param  string $coleccion ID de la colección.
     */
    public static function coleccion(string $coleccion)
    {
        ?>
        <iframe class="twitch-video"
            src="http://player.twitch.tv/?collection=<?= $coleccion ?>"
            height="720"
            width="80%"
            frameborder="0"
            scrolling="no"
            allowfullscreen="true">
        </iframe>
        <?php
    }
}
