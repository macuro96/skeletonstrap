<?php

namespace common\components;

use yii\helpers\Html;

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
        src="https://player.twitch.tv/?channel=<?= Html::encode(Config::find()->one()->usuario_twitch) ?>"
        height="720"
        width="90%"
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
            src="https://player.twitch.tv/?collection=<?= Html::encode($coleccion) ?>&autoplay=false"
            height="720"
            width="80%"
            frameborder="0"
            scrolling="no"
            allowfullscreen="true">
        </iframe>
        <?php
    }
}
