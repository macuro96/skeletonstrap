<?php
/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Cambiar información de mi perfil';
?>
<div class="row seccion usuarios-form">
    <div class="col-lg-12">
        <h2>Actualizar mi perfil</h2>

        <div>
            <?php $form = ActiveForm::begin(['id' => 'usuarios-form']); ?>

                <?= $form->field($model, 'correo')->textInput(['maxlength' => true])
                                                  ->label('Correo*')
                ?>
                <?= $form->field($model, 'nacionalidad_id')->dropDownList($nacionalidades, ['value' => 48])->label('Nacionalidad*') ?>
                <?= $form->field($model, 'zona_horaria_id')->dropDownList($zonasHorarias, ['value' => 13])->label('Zona Horaria*') ?>
                <hr>
                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'password_repeat')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Actualizar', ['class' => 'btn btn-success']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
