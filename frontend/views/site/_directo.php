<?php

use yii\helpers\Html;

use common\components\CheckEnd;

?>
<div class="row div-directo">
    <div class="col-md-12">
        <div class="row equipos">
            <div class="col-md-4">
                <?= Html::img(CheckEnd::rutaRelativa() . 'images/logo.png', ['class' => 'img-responsive']) ?>
            </div>
            <div class="col-md-4">
                VS
            </div>
            <div class="col-md-4">
                <?= Html::img(CheckEnd::rutaRelativa() . 'images/logo.png', ['class' => 'img-responsive']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 texto-directo">
                <h2>EN DIRECTO<i class="fa fa-circle"></i></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <iframe
                src="http://player.twitch.tv/?channel=skeletonstraptv"
                height="720"
                width="100%"
                frameborder="0"
                scrolling="no"
                allowfullscreen="true">
                </iframe>
            </div>
        </div>
    </div>
</div>
