<?php

use yii\helpers\Html;

use common\components\Recursos;

?>
<div class="col-md-4 col-sm-6">
    <div class="jugador">
        <?= Recursos::imageCommon('perfil.png', ['class' => 'img-perfil img-resposive']) ?>
        <span class="nombre"><?= Html::encode($model->jugadores->nombre) ?></span>
        <div class="datos">
            <div class="row">
                <div class="col-md-6 nombre-dato">
                    Nacionalidad
                </div>
                <div class="col-md-6 valor-dato">
                    <?= Html::encode($model->nacionalidad->nombre) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 nombre-dato">
                    <?= Recursos::imageCommon('trophy.png', ['class' => 'img-icon img-resposive']) ?> Copas
                </div>
                <div class="col-md-6 valor-dato">
                    <?= Html::encode($model->jugadores->copas) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 nombre-dato">
                    <?= Recursos::imageCommon('battle.png', ['class' => 'img-icon img-resposive']) ?> Victorias
                </div>
                <div class="col-md-6 valor-dato">
                    <?= Html::encode($model->jugadores->victorias) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 nombre-dato">
                    <?= Recursos::imageCommon('ligas/' . $model->jugadores->liga->icono . '.png', ['class' => 'img-icon img-resposive']) ?> Arena
                </div>
                <div class="col-md-6 valor-dato">
                    <?= Html::encode($model->jugadores->liga->nombre) ?>
                </div>
            </div>
        </div>
    </div>
</div>
