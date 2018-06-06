<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $nacionalidades common\models\Nacionalidades */
/* @var $model common\models\SolicitudLucha */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Solicitud de lucha';

?>
<div class="row seccion usuarios-form">
    <div class="col-lg-12">
        <h2>¡Quiero luchar contra Skeleton's Trap!</h2>

        <div>
            <ul>
                <li>
                    Este formulario generará una solicitud al administrador. Si el administrador acepta, le enviará un correo con más
                    información.
                </li>
            </ul>

            <?php $form = ActiveForm::begin(['id' => 'usuarios-form']); ?>

                <?= $form->field($model, 'tag')->textInput(['maxlength' => true])
                                               ->label('TAG del clan*')
                                               ->hint('Debe ser un TAG  real de un clan del juego.')
                ?>
                <?= $form->field($model, 'correo')->textInput(['maxlength' => true])
                                                  ->label('Correo*')
                ?>
                <?= $form->field($model, 'nacionalidad_id')->dropDownList($nacionalidades, ['value' => 48])->label('Nacionalidad*') ?>

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
