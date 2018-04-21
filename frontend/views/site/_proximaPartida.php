<?php

/* @var $detect Detection\MobileDetect */

use yii\helpers\Html;
use common\components\CheckEnd;
use common\components\Recursos;
use common\components\RedesSociales;
?>
<div class="row proxima-partida">
    <div class="col-md-offset-2 col-md-8">
        <div class="row text-centered div-titulo">
            <div class="col-md-12">
                <h2 class="titulo-proxima-partida">PRÓXIMA PARTIDA <span class="glyphicon glyphicon-refresh btn" aria-hidden="true"></span></h2>
            </div>
        </div>
        <div class="row text-centered div-subtitulo">
            <div class="col-md-12 redes">
                <h3 class="subtitulo-proxima-partida"><span class="glyphicon glyphicon-time" aria-hidden="true"></span> <hora>14:00 PM</hora> <a href="https://www.twitch.tv/skeletonstraptv" class="twitch"><i class="fa fa-twitch"></i></a></h3>
            </div>
        </div>
        <div class="row">
            <div class="logo logo-equipo col-md-5 col-sm-5 col-xs-6 img-centered">
                <div class="row">
                    <div class="col-md-12">
                        <?= Recursos::imageCommon('logo.png') ?>
                    </div>
                </div>
                <div class="row nombres-equipos">
                    <div class="col-md-12 equipo">
                        <h3>Skeleton's Trap</h3>
                    </div>
                </div>
            </div>
            <div id="versus-image" class="col-md-2 col-sm-2 img-centered hidden-xs">
                <div class="row">
                    <div class="col-md-12">
                        <?= Recursos::imageCommon('versus.png') ?>
                    </div>
                </div>
            </div>
            <div class="logo logo-enemigo col-md-5 col-sm-5 col-xs-6 img-centered">
                <div class="row">
                    <div class="col-md-12">
                        <?= Recursos::imageCommon('Logo4K1.png.png') ?>
                    </div>
                </div>
                <div class="row nombres-equipos">
                    <div class="col-md-12 equipo-enemigo">
                        <h3>TeamQueso</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="redes-sociales centrar">
            <?= RedesSociales::twitter('jaskdbfjaskdbf') ?>
            <?= RedesSociales::whatsapp($detect, 'https://skeletons-trap.herokuapp.com/ ¿Te atréves a jugar contra nosotros? Somos Skeleton\'s Trap') ?>
        </div>
    </div>
</div>
