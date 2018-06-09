<?php

/* @var $detect Detection\MobileDetect */

use yii\helpers\Html;

use common\components\RedesSociales;
use common\components\Twitch;

?>
<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Mejores partidas</h2></div>
        <div class="contenido-seccion">
            <div class="row">
                <div class="col-lg-12 centrar">
                    <?= Twitch::coleccion(Html::encode($config->coleccion_twitch)) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 centrar">
                    <h2><?= Html::a('¿Te atréves a jugar <b>contra nosotros</b>?', ['/site/luchar'], ['class' => 'a-none']) ?></h2>
                    <div class="redes-sociales">
                        <?= RedesSociales::twitter(Html::encode($config->mensaje_twitter)) ?>
                        <?= RedesSociales::whatsapp($detect, Html::encode($config->mensaje_whatsapp)) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
