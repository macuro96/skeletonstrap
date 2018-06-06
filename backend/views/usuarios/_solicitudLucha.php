<?php
/* @var $model common\models\SolicitudLucha */

use yii\helpers\Html;
?>
<div class="solicitud-lucha">
    <div class="row">
        <div class="col-md-12 centrar">
            <div>
                <b><?= Html::encode($model->clan->nombre) ?></b>
            </div>
            <div>
                #<?= Html::encode($model->clan->tag) ?>
            </div>
        </div>
        <div class="col-md-12 botones">
            <?php if (!$model->aceptada) : ?>
                <?= Html::a('Aceptar', ['aceptar-solicitud-lucha'], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'method' => 'post',
                        'params' => ['solicitud' => $model->id]
                    ]
                ]) ?>
            <?php else : ?>
                <?= Html::a('Volver a enviar correo', ['correo-solicitud-lucha'], [
                    'class' => 'btn btn-pendiente',
                    'data' => [
                        'method' => 'post',
                        'params' => ['solicitud' => $model->id]
                    ]
                ]) ?>
            <?php endif; ?>
            <?= Html::a('Borrar', ['borrar-solicitud-lucha'], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => "¿Estás seguro que quieres borrar la solicitud de lucha del clan " . $model->clan->nombre . "?",
                    'method' => 'post',
                    'params' => ['solicitud' => $model->id]
                ]
            ]) ?>
        </div>
    </div>
</div>
