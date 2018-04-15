<?php
    /* @var $model common\models\Usuarios */

    use yii\helpers\Html;
    use backend\components\Ruta;
?>
<div class="usuario" data-usuario="<?= Html::encode($model->id) ?>">
    <div class="row">
        <div class="col-lg-offset-3 col-md-offset-1 col-lg-1 col-md-1 img-centered">
            <?= Html::img(Ruta::to('images/perfil.png'), ['class' => 'img-responsive img-perfil']) ?>
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
            <?php if (!$model->estaVerificado) : ?>
                <?= Html::a('Aceptar', ['aceptar'], ['class' => 'btn btn-success btn-lg']) ?>
                <?= Html::a('Cancelar', ['cancelar'], ['class' => 'btn btn-danger btn-lg']) ?>
            <?php else : ?>
                <?= Html::a('Pendiente de verificar', ['correo-verificar'], ['class' => 'btn btn-pendiente btn-lg']) ?>
            <?php endif; ?>
        </div>
    </div>
</div>
