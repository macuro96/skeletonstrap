<?php

namespace common\components;

use yii\helpers\Html;

class Partidas
{
    public static function mostrarPartida()
    {
        ?>
        <div class="row equipos">
            <div class="col-md-offset-3 col-md-2">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-12">
                        <div class="container-img">
                            <?= Html::img(CheckEnd::rutaRelativa() . 'images/logo.png', ['class' => 'img-responsive']) ?>
                        </div>
                        <div>
                            <p>Skeleton's Trap</p>
                        </div>
                    </div>
                    <div class="col-sm-4 visible-sm guion">
                        -
                    </div>
                    <div class="col-sm-4 visible-sm marcador-local">
                        <marcadorlocal>0</marcadorlocal>
                    </div>
                </div>
                <div class="row hidden-sm">
                    <div class="col-md-12">
                        <marcadorlocal>0</marcadorlocal>
                    </div>
                </div>
            </div>
            <div class="col-md-2 compartir-redes">
                <div>
                    VS
                </div>
                <div class="compartir">
                    <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> compartir
                </div>
                <?= RedesSociales::twitter('Partida') ?>
                <?= RedesSociales::facebook() ?>
            </div>
            <div class="col-md-2">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-12">
                        <div class="container-img">
                            <?= Html::img(CheckEnd::rutaRelativa() . 'images/Logo4K1.png', ['class' => 'img-responsive']) ?>
                        </div>
                        <div>
                            <p>RoyaleconQueso</p>
                        </div>
                    </div>
                    <div class="col-sm-4 visible-sm guion">
                        -
                    </div>
                    <div class="col-sm-4 visible-sm marcador-local">
                        <marcadorlocal>0</marcadorlocal>
                    </div>
                </div>
                <div class="row hidden-sm">
                    <div class="col-md-12">
                        <marcadorlocal>0</marcadorlocal>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
