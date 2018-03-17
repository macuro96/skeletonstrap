<?php

use yii\helpers\Html;

use common\components\CheckEnd;

$this->registerCssFile('css/_directo.css');
?>
<div class="row div-directo">
    <div class="col-md-12">
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
            <div class="col-md-2">
                VS
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
    </div>
</div>
