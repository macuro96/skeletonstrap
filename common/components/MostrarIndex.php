<?php

namespace common\components;

use yii\helpers\Html;

class MostrarIndex
{
    public static function directo($detect, $etiqueta, $nombreEvento = null)
    {
        $etiqueta = mb_strtoupper($etiqueta);

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
                            <h3 class="subtitulo-proxima-partida">- <etiqueta><?= $etiqueta ?></etiqueta><?= $nombreEvento != null ? ": $nombreEvento " : '' ?> -</h3>
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
                                <?= Html::img(CheckEnd::rutaRelativa() . 'images/logo.png', ['class' => 'img-responsive']) ?>
                            </div>
                        </div>
                        <div class="row nombres-equipos">
                            <div class="col-md-12 equipo">
                                <h3>Skeleton's Trap</h3>
                                <h3><marcador>0</marcador></h3>
                            </div>
                        </div>
                    </div>
                    <div id="versus-image" class="col-md-2 col-sm-2 img-centered hidden-xs">
                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::img(CheckEnd::rutaRelativa() . 'images/versus.png', ['class' => 'img-responsive']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="logo logo-enemigo col-md-5 col-sm-5 col-xs-6 img-centered">
                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::img(CheckEnd::rutaRelativa() . 'images/Logo4K1.png', ['class' => 'img-responsive']) ?>
                            </div>
                        </div>
                        <div class="row nombres-equipos">
                            <div class="col-md-12 equipo-enemigo">
                                <h3>TeamQueso</h3>
                                <h3><marcador>0</marcador></h3>
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
        <?php
    }

    public static function proximaPartida($detect)
    {
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
                                <?= Html::img(CheckEnd::rutaRelativa() . 'images/logo.png', ['class' => 'img-responsive']) ?>
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
                                <?= Html::img(CheckEnd::rutaRelativa() . 'images/versus.png', ['class' => 'img-responsive']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="logo logo-enemigo col-md-5 col-sm-5 col-xs-6 img-centered">
                        <div class="row">
                            <div class="col-md-12">
                                <?= Html::img(CheckEnd::rutaRelativa() . 'images/Logo4K1.png', ['class' => 'img-responsive']) ?>
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
        <?php
    }

    public static function mejoresPartidas($coleccion)
    {
        echo Twitch::coleccion($coleccion);
    }
}
