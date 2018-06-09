<?php
/* @var $this yii\web\View */
/* @var $model backend\models\Directo */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\models\Directo;

use common\components\RegisterThisCss;

$this->title = 'Modificar directo actual';

RegisterThisCss::register($this);
?>
<div class="row seccion usuarios-form">
    <div class="col-lg-12">
        <h2>Modificar directo actual</h2>

        <div>
            <?php $form = ActiveForm::begin(['id' => 'web-directo-form']); ?>
                <h3>Encabezado</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, "titulo")->textInput(['maxlength' => true])
                                                          ->label('Título*')
                                                          ->hint('Se mostrará arriba del video, por ejemplo: partido, torneo, etc.')
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, "subtitulo")->textInput(['maxlength' => true])
                                                             ->hint('Opcional. Se mostrará junto al título, se separá automaticamente por un guión.')
                        ?>
                    </div>
                </div>

                <h3>Redes sociales</h3>
                <div class="row">
                    <div class="col-lg-6">
                        <?= $form->field($model, "mensaje_twitter")->textInput(['maxlength' => true])
                                                                   ->label('Mensaje de compartir en twitter*')
                                                                   ->hint('Se utilizará para el mensaje que aparece al compartir en twitter el directo.')
                        ?>
                    </div>
                    <div class="col-lg-6">
                        <?= $form->field($model, "mensaje_whatsapp")->textInput(['maxlength' => true])
                                                                    ->label('Mensaje de compartir en whatsapp*')
                                                                    ->hint('Se utilizará para el mensaje que aparece al compartir en whatsapp el directo.')
                        ?>
                    </div>
                </div>

                <h3>Partido</h3>
                <div class="row">
                    <div class="col-lg-3">
                        <?= $form->field($model, "oponente_tag")->textInput(['maxlength' => true])
                                                                ->label('TAG del equipo oponente*')
                                                                ->hint('TAG real de un clan.')
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <?php if ($model->scenario == Directo::ESCENARIO_UPDATE) : ?>
                            <img class="img-file" src="<?= Html::encode($model->getLogoSrc()) ?>">
                        <?php endif; ?>
                        <?= $form->field($model, "file")->fileInput()
                                                        ->label('Logo equipo oponente*')
                                                        ->hint('Logo en formato png.')
                        ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-1">
                        <?= $form->field($model, "marcador_propio")->textInput(['maxlength' => true, 'type' => 'number'])
                                                                   ->label('Equipo')
                                                                   ->hint('Marcador de Skeletons\' Trap actualmente.')
                        ?>
                    </div>
                    <div class="col-lg-1">
                        <?= $form->field($model, "marcador_oponente")->textInput(['maxlength' => true, 'type' => 'number'])
                                                                     ->label('Oponente')
                                                                     ->hint('Marcador del equipo oponente actualmente.')
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
