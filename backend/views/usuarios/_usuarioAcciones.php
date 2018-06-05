<?php
/* @var $model common\models\Usuarios */

use yii\helpers\Html;

$configEliminar = [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => "¿Estás seguro que quieres borrar al usuario $model->nombre?",
        'method' => 'post',
        'params' => ['usuario' => $model->id]
    ]
];
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
            <?= Html::a('Eliminar', ['eliminar-usuario'], $configEliminar) ?>
        </div>
    </div>
</div>
