<?php

namespace common\components;

class DirectoIndex
{
    public static function mostrarDirecto()
    {
        ?>
        <div class="row">
            <div class="col-md-12 texto-directo">
                <h2>EN DIRECTO<i class="fa fa-circle"></i></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 twitch-video">
                <iframe
                src="http://player.twitch.tv/?channel=skeletonstraptv"
                height="720"
                width="80%"
                frameborder="0"
                scrolling="no"
                allowfullscreen="true">
                </iframe>
            </div>
        </div>
        <?php
    }

    public static function mostrarProximaPartida()
    {
        ?>
        <div class="row">
            <div class="col-md-12 texto-directo">
                <h2>PRÓXIMA PARTIDA</h2>
            </div>
        </div>
        <div class="row proxima-partida">
            <div class="col-md-offset-4 col-md-4">
                <span><horario>18:30</horario></span>
                <div>
                    <p>FALTAN</p>
                    <p><tiempo>02:31:40</tiempo> Hrs</p>
                </div>
            </div>
        </div>
        <?php
    }
}
