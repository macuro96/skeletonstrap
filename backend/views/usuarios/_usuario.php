<?php
/* @var $model common\models\Usuarios */

use yii\helpers\Html;
?>
<div class="usuario" data-usuario="<?= Html::encode($model->id) ?>">
    <div class="row">
        <div class="col-md-12 centrar">
            <div>
                <b><?= Html::encode($model->nombre) ?></b>
            </div>
            <div>
                #<?= Html::encode($model->jugadores->tag) ?>
            </div>
        </div>
        <div class="col-md-12 botones">
            <?php if (!$model->estaActivo) : ?>
                <?= Html::a('Aceptar', ['aceptar-solicitud'], [
                    'class' => 'btn btn-success',
                    'data' => [
                        'method' => 'post',
                        'params' => ['usuario' => $model->id]
                    ]
                ]) ?>
            <?php else : ?>
                <?= Html::a('Pendiente de verificar', ['correo-verificar'], [
                    'class' => 'btn btn-pendiente',
                    'data' => [
                        'method' => 'post',
                        'params' => ['usuario' => $model->id]
                    ]
                ]) ?>
            <?php endif; ?>
            <?= Html::a('Cancelar', ['cancelar-solicitud'], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => "¿Estás seguro que quieres cancelar la solicitud del jugador $model->nombre?",
                    'method' => 'post',
                    'params' => ['usuario' => $model->id]
                ]
            ]) ?>
        </div>
    </div>
</div>
