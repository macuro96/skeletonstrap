<?php
/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Eliminar a un usuario';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['usuarios/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row seccion usuarios-form">
    <div class="col-lg-5">
        <h2>Eliminar a un usuario</h2>

        <div>
            <?php $form = ActiveForm::begin(['id' => 'usuarios-form']); ?>
                <?= $form->field($model, 'usuario_id')->dropDownList($usuarios) ?>

                <div class="form-group">
                    <?= Html::submitButton('Eliminar', ['class' => 'btn btn-danger']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
