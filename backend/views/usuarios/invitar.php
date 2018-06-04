<?php
/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Nueva invitación';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['usuarios/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row seccion usuarios-form">
    <div class="col-lg-12">
        <h2>Nueva invitación</h2>

        <div>
            <ul>
                <li>
                    Este formulario generará un correo que se enviará de forma automática al correo propocionado, de manera que
                    el usuario tenga que verificar su cuenta desde el enlace enviado introduciendo el código del mensaje.
                </li>
                <li>
                    El usuario que verifique su cuenta tendrá que aceptar las normas del equipo y cambiar su contraseña para acceder a la aplicación.
                </li>
            </ul>

            <?php $form = ActiveForm::begin(['id' => 'usuarios-form']); ?>

                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true])
                                                  ->hint('Con el que se identificará la cuenta para el inicio de sesión.')
                                                  ->label('Nombre de usuario*')
                ?>
                <?= $form->field($model, 'correo')->textInput(['maxlength' => true])
                                                  ->label('Correo*')
                ?>
                <?= $form->field($model, 'nacionalidad_id')->dropDownList($nacionalidades, ['value' => 48])->label('Nacionalidad*') ?>
                <?= $form->field($model, 'zona_horaria_id')->dropDownList($zonasHorarias, ['value' => 13])->label('Zona Horaria*') ?>
                <?= $form->field($model, 'tag')->textInput(['maxlength' => true])
                                               ->label('TAG*')
                                               ->hint('Debe ser un TAG real de un jugador del juego.')
                ?>

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
