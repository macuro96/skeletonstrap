<?php

/* @var $detect       Detection\MobileDetect */
/* @var $titulo       Etiqueta de partida */
/* @var $subtitulo    Nombre del evento asociado a la partida */

use yii\helpers\Html;
use common\components\Recursos;
use common\components\RedesSociales;
use common\components\Twitch;

$titulo     = mb_strtoupper($titulo);
$subtitulo  = $subtitulo ?? '';
?>
<div class="row proxima-partida directo">
    <div class="col-md-offset-2 col-md-8">
        <div class="row text-centered div-titulo">
            <div class="col-md-12">
                <h2 class="titulo-proxima-partida titulo-directo">EN DIRECTO <i class="fa fa-circle"></i></h2>
            </div>
        </div>
        <div class="row text-centered div-subtitulo">
            <div class="col-md-12">
                <!--<a href="#">-->
                    <h3 class="subtitulo-proxima-partida">- <titulo><?= Html::encode($titulo) ?></titulo><subtitulo><?= $subtitulo != null ? ": " . Html::encode($subtitulo) . "" : '' ?></subtitulo> -</h3>
                <!--</a>-->
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 centrar div-directo">
                <?= Twitch::directo() ?>
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
                        <h3><marcador><?= Html::encode($marcadorPropio) ?></marcador></h3>
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
                        <img src='<?= Html::encode($logoOponente) ?>' class="img-responsive">
                    </div>
                </div>
                <div class="row nombres-equipos">
                    <div class="col-md-12 equipo-enemigo">
                        <h3><?= Html::encode($nombreEquipoOponente) ?></h3>
                        <h3><marcador><?= Html::encode($marcadorOponente) ?></marcador></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="redes-sociales centrar">
            <?= RedesSociales::twitter(Html::encode($msgTwitter)) ?>
            <?= RedesSociales::whatsapp($detect, Html::encode($msgWhatsapp)) ?>
        </div>
    </div>
</div>
