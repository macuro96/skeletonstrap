<?php

use yii\helpers\Html;

use common\models\ConfigTiempoActualizado;

use common\components\Recursos;
?>
<span class="nombre"><?= Html::encode($model->jugadores->nombre) ?></span> <span class="rol">(<?= Html::encode($model->jugadores->clan_rol) ?>)</span>
<div class="datos">
    <div class="row ultima-act">
        <div class="col-md-6">
            <?php if (ConfigTiempoActualizado::ultimaActualizacionJugador($model->jugadores->tag)) : ?>
                Actualización disponible (+14 min).
            <?php else : ?>
                Actualizado (-14 min).
            <?php endif; ?>
        </div>
        <div class="col-md-6 btn-actualizar">
            <?php
            $class = ['btn-xs', 'btn', 'btn-success', 'btn-actualizar-miembro'];
            ?>
            <?= Html::a('Actualizar', ['equipo/actualizar-miembro'], [
                'class' => $class,
            ]) ?>
        </div>
    </div>
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
