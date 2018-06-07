<?php

/* @var $detect Detection\MobileDetect */

use yii\helpers\Html;
use common\models\Config;

use common\components\RedesSociales;
use common\components\Twitch;

?>
<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Mejores partidas</h2></div>
        <div class="contenido-seccion">
            <div class="row">
                <div class="col-lg-12 centrar">
                    <?= Twitch::coleccion('8NTmRZP42xSlkg') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 centrar">
                    <h2><?= Html::a('¿Te atréves a jugar <b>contra nosotros</b>?', ['/site/luchar'], ['class' => 'a-none']) ?></h2>
                    <div class="redes-sociales">
                        <?= RedesSociales::twitter(Config::find()->one()->mensaje_twitter) ?>
                        <?= RedesSociales::whatsapp($detect, Config::find()->one()->mensaje_whatsapp) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
