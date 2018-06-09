<?php
/* @var $this yii\web\View */
/* @var $model backend\models\Config */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Parámetros de configuración web';

?>
<div class="row seccion usuarios-form">
    <div class="col-lg-12">
        <h2>Parámetros de configuración web</h2>

        <div>
            <?php $form = ActiveForm::begin(['id' => 'config-web-parametros-form']); ?>
                <h3>Twitter</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, "mensaje_twitter")->textInput(['maxlength' => true])
                                                                   ->label('Mensaje de compartir*')
                                                                   ->hint('Se utilizará para el mensaje por defecto de compartir en twitter.')
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, "mensaje_unete_twitter")->textInput(['maxlength' => true])
                                                                   ->label('Mensaje de únete*')
                                                                   ->hint('Se utilizará para el mensaje del tweet para pedir unirse al equipo (tweet de cuenta logueada en el dispositivo).')
                        ?>
                    </div>
                </div>

                <h3>Whatsapp</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, "mensaje_whatsapp")->textInput(['maxlength' => true])
                                                                   ->label('Mensaje de compartir*')
                                                                   ->hint('Se utilizará para el mensaje por defecto de compartir en whatsapp.')
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, "mensaje_unete_whatsapp")->textInput(['maxlength' => true])
                                                                   ->label('Mensaje de únete*')
                                                                   ->hint('Se utilizará para el mensaje del whatsapp para pedir unirse al equipo (whatsapp del que comparte).')
                        ?>
                    </div>
                </div>

                <h3>Twitch</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, "usuario_twitch")->textInput(['maxlength' => true])
                                                                   ->label('Usuario*')
                                                                   ->hint('Se utilizará para acceder al contenido del usuario de twitch.')
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, "coleccion_twitch")->textInput(['maxlength' => true])
                                                                    ->hint('Se utilizará para mostrar la colección de videos en la sección de mejores partidas de la página principal.')
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
