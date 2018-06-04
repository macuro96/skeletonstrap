<?php
/* @var $this yii\web\View */
/* @var $model common\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\assets\CommonFormNuevoUsuario;

$this->title = 'Únete';
$this->params['breadcrumbs'][] = $this->title;

CommonFormNuevoUsuario::register($this);
?>
<div class="row seccion usuarios-form">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>¡Únete a nosotros!</h2></div>

        <div class="contenido-seccion">
            <ul class="pasos">
                <li>
                    Este formulario generará una nueva petición que será enviada al administrador. Si la petición es aceptada se enviará de forma automática un correo de verificación al usuario.
                </li>
                <li>
                    El usuario que verifique su cuenta tendrá que cambiar su contraseña para acceder a la aplicación.
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
                <?= $form->field($model, 'nacionalidad_id')->dropDownList($nacionalidades, ['value' => 48])->label('Nacionalidad*')
                ?>
                <?= $form->field($model, 'zona_horaria_id')->dropDownList($zonasHorarias, ['value' => 13])->label('Zona Horaria*')
                ?>
                <?= $form->field($model, 'tag')->textInput(['maxlength' => true])
                                               ->label('TAG*')
                                               ->hint('Debe ser un TAG real de un jugador del juego.')
                ?>
                <?= $form->field($model, 'normas')->checkbox()
                                                  ->hint('Se deben aceptar las normas para poder optar por una cuenta en la aplicación.')
                ?>

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
