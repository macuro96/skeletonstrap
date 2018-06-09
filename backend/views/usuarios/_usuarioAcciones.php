<?php
/* @var $model common\models\Usuarios */

use yii\helpers\Html;

$config = [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => "¿Estás seguro que quieres borrar al usuario $model->nombre?",
        'method' => 'post',
        'params' => ['usuario' => $model->id]
    ]
];

$configEliminar = $config;
$configEliminar['class'] = 'btn btn-danger';
$configEliminar['data']['confirm'] = "¿Estás seguro que quieres borrar al usuario $model->nombre?";

$configExpulsar = $config;
$configExpulsar['class'] = 'btn btn-warning';
$configExpulsar['data']['confirm'] = "¿Estás seguro que quieres expulsar al usuario $model->nombre?";

$configQuitarExpulsion = $config;
$configQuitarExpulsion['class'] = 'btn btn-success';
$configQuitarExpulsion['data']['confirm'] = "¿Estás seguro que quieres quitar la expulsión al usuario $model->nombre?";

?>
<div class="usuario" data-usuario="<?= Html::encode($model->id) ?>">
    <div class="row">
        <div class="col-md-12 centrar">
            <div>
                <b><?= Html::encode($model->nombre) ?></b>
            </div>
            <div>
                <?= Html::encode(isset($model->jugadores) ? ('#' . $model->jugadores->tag) : '<sin tag>') ?>
            </div>
        </div>
        <div class="col-md-12 botones">
            <?php if (!$model->estaExpulsado) : ?>
                <?= Html::a('Expulsar', ['expulsar'], $configExpulsar) ?>
            <?php else : ?>
                <?= Html::a('Quitar expulsión', ['quitar-expulsion'], $configQuitarExpulsion) ?>
            <?php endif; ?>
            <?= Html::a('Eliminar', ['eliminar'], $configEliminar) ?>
        </div>
    </div>
</div>
