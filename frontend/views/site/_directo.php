<?php

use yii\web\View;

use yii\helpers\Html;

use common\components\CheckEnd;
use common\components\DirectoIndex;
use common\components\RedesSociales;

$this->registerCssFile('css/_directo.css');
$this->registerJsFile('js/_directo.js', ['position' => View::POS_END,
                                         'depends'  => [\yii\web\JqueryAsset::className()]]);
?>
<div class="row div-directo">
    <div class="col-md-12">
        <?= DirectoIndex::mostrarDirecto() ?>
        <div id="etiquetaDirecto" class="row etiquetas">
            <span>
                Torneo
            </span>
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
        <div id="etiquetaNoticias" class="row etiquetas">
            <span>
                Últimas noticias
            </span>
        </div>
        <div class="row noticias">
            <div class="col-md-offset-3 col-md-6">
                <?= RedesSociales::timelineTwitter() ?>
            </div>
        </div>

    </div>
</div>
