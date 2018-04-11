<?php

namespace common\components;

use yii\helpers\Html;

class Twitch
{
    public static function directo()
    {
        ?>
        <iframe class="twitch-video"
        src="http://player.twitch.tv/?channel=skeletonstraptv"
        height="720"
        width="80%"
        frameborder="0"
        scrolling="no"
        allowfullscreen="true">
        </iframe>
        <?php
    }

    public static function coleccion($coleccion)
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
