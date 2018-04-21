<?php
/* @var $model common\models\Usuarios */

use yii\helpers\Html;
use common\components\Recursos;
?>
<div class="usuario" data-usuario="<?= Html::encode($model->id) ?>">
    <div class="row">
        <div class="col-lg-offset-3 col-md-offset-1 col-lg-1 col-md-1 img-centered">
            <?= Recursos::image('perfil.png', ['class' => 'img-responsive img-perfil']) ?>
        </div>
        <div class="col-lg-3 col-md-7 datos">
            <h5>Usuario:</h5>
            <p>
                <?= Html::encode($model->nombre) ?>
            </p>
            <h5>Correo:</h5>
            <p>
                <?= Html::encode($model->correo) ?>
            </p>
        </div>
        <div class="col-lg-2 col-md-2 botones">
            <?php if (!$model->estaActivo) : ?>
                <?= Html::a('Aceptar', ['aceptar-solicitud'], [
                    'class' => 'btn btn-success btn-lg',
                    'data' => [
                        'method' => 'post',
                        'params' => ['usuario' => $model->id]
                    ]
                ]) ?>
            <?php else : ?>
                <?= Html::a('Pendiente de verificar', ['correo-verificar'], [
                    'class' => 'btn btn-pendiente btn-lg',
                    'data' => [
                        'method' => 'post',
                        'params' => ['usuario' => $model->id]
                    ]
                ]) ?>
            <?php endif; ?>
            <?= Html::a('Cancelar', ['cancelar-solicitud'], [
                'class' => 'btn btn-danger btn-lg',
                'data' => [
                    'confirm' => "¿Estás seguro que quieres cancelar la solicitud del jugador $model->nombre?",
                    'method' => 'post',
                    'params' => ['usuario' => $model->id]
                ]
            ]) ?>
        </div>
    </div>
</div>
