<?php

use yii\helpers\Html;

use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Calendario */
?>
<div class="row seccion usuarios-form">
    <div class="col-lg-12">
        <?php $form = ActiveForm::begin(['id' => 'calendario-form']); ?>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, "etiqueta")->dropDownList($etiquetas)
                                                           ->label('Etiqueta*');
                                                           ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?= $form->field($model, "visibilidad")->dropDownList($rolesVisibilidad)
                                                           ->label('Visibilidad')
                                                           ->hint('El rol de visilibidad que se le va a dar al evento. Si no se especifíca, todos podrán verlo (incluido invitados).');
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2">
                    <?= $form->field($model, 'fecha')->widget(DatePicker::className(), [
                        'model' => $model,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'dd-mm-yyyy',
                        ]
                    ])->label('Fecha*') ?>
                </div>
                <div class="col-lg-2">
                    <?= $form->field($model, 'hora')->widget(TimePicker::className(), [
                        'model' => $model,
                        'pluginOptions' => [
                            'autoclose'=>true,
                            'format' => 'H:i',
                            'showMeridian' => false,
                        ]
                    ])->label('Hora*') ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <?= $form->field($model, "descripcion")->textarea()
                                                           ->label('Descripción*')
                    ?>
                </div>
            </div>

            <hr>

            <div class="form-group">
                <?= Html::submitButton('Guardar', ['class' => 'btn btn-success', 'id' => 'btn-accion']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
