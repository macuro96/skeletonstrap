<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Calendario */

$this->title = 'Calendario';

$eventosKeys  = array_keys($eventos);
$primerEvento = isset($eventosKeys[0]) ? $eventosKeys[0] : null;

$js = <<<EOT
    $('select[name="eventos"]').on('change', function () {
        $('#btn-actualizar').attr('href', '/admin/calendario/update?evento='+$(this).val());
        console.log();

        var id = $(this).val();

        $('#btn-borrar').attr('data-params', JSON.stringify({evento: id}));
    });
EOT;

$this->registerJs($js);
?>
<div class="row seccion usuarios-form">
    <div class="col-lg-12">
        <h2>Eventos</h2>

        <?php if (\Yii::$app->authManager->checkAccess(\Yii::$app->user->identity->id, 'programarEvento')) : ?>
            <?= Html::a('Crear nuevo', ['create'], ['class' => ['btn btn-success']]) ?>
        <?php endif; ?>

        <hr>

        <?php if ($eventos) : ?>
            <?= Html::dropDownList('eventos', null, $eventos); ?>
        <?php endif; ?>

        <div class="form-group">
            <?php if (\Yii::$app->authManager->checkAccess(\Yii::$app->user->identity->id, 'modificarEvento') && $eventos) : ?>
                <?= Html::a('Actualizar', ['update', 'evento' => $primerEvento], ['class' => ['btn btn-success'], 'id' => 'btn-actualizar']) ?>
            <?php endif; ?>
            <?php if (\Yii::$app->authManager->checkAccess(\Yii::$app->user->identity->id, 'eliminarEvento') && $eventos) : ?>
                <?= Html::a('Borrar', ['delete'], [
                    'class' => ['btn btn-danger'],
                    'id' => 'btn-borrar',
                    'data' => [
                        'method' => 'post',
                        'confirm' => '¿Seguro que quieres borrar este evento?',
                        'params' => ['evento' => $primerEvento]
                    ]
                ]) ?>
            <?php endif; ?>
        </div>
    </div>
</div>
