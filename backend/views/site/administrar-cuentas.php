<?php
/* @var $this yii\web\View */
/* @var $model backend\models\Config */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Configuración de cuentas';

?>
<div class="row seccion usuarios-form">
    <div class="col-lg-12">
        <h2>Configuración de cuentas</h2>

        <div>
            <?php $form = ActiveForm::begin(['id' => 'config-cuentas-form']); ?>
                <h3>Twitter</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, "mensaje_twitter")->textInput(['maxlength' => true])
                                                                   ->label('Mensaje de compartir Twitter*')
                                                                   ->hint('Se utilizará para el mensaje por defecto de compartir en twitter.')
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, "mensaje_unete_twitter")->textInput(['maxlength' => true])
                                                                   ->label('Mensaje de únete Twitter*')
                                                                   ->hint('Se utilizará para el mensaje del tweet para pedir unirse al equipo (tweet de @SkeletonsTrapCR).')
                        ?>
                    </div>
                </div>

                <h3>Whatsapp</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, "mensaje_whatsapp")->textInput(['maxlength' => true])
                                                                   ->label('Mensaje de compartir Whatsapp*')
                                                                   ->hint('Se utilizará para el mensaje por defecto de compartir en whatsapp.')
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, "mensaje_unete_whatsapp")->textInput(['maxlength' => true])
                                                                   ->label('Mensaje de únete Whatsapp*')
                                                                   ->hint('Se utilizará para el mensaje del whatsapp para pedir unirse al equipo (whatsapp del que comparte).')
                        ?>
                    </div>
                </div>

                <h3>Twitch</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, "usuario_twitch")->textInput(['maxlength' => true])
                                                                   ->label('Usuario Twitch*')
                                                                   ->hint('Se utilizará para acceder al contenido del usuario de twitch.')
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
