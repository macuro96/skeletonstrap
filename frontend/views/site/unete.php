<?php
/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use backend\components\Ruta;

$this->title = 'Únete';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['usuarios/index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(Ruta::to('css/unete.css'));
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
                                                  ->hint('Debe coincidir exactamente (mayúsculas y minúsculas, numeros, etc) con el nombre del jugador de Clash Royale.')
                                                  ->label('Nombre*')
                ?>
                <?= $form->field($model, 'correo')->textInput(['maxlength' => true])
                                                  ->label('Correo*')
                ?>
                <?= $form->field($model, 'nacionalidad_id')->dropDownList([
                    [1 => 'ESP']
                ])
                ?>
                <?= $form->field($model, 'normas')->checkbox()
                                                  ->hint('Se deben aceptar las normas para poder optar por una cuenta en la aplicación')
                ?>

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
