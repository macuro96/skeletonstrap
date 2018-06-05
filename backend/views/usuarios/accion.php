<?php
/* @var $this yii\web\View */
/* @var $model app\models\Usuarios */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Eliminar o expulsar un usuario';
$this->params['breadcrumbs'][] = ['label' => 'Usuarios', 'url' => ['usuarios/index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row seccion usuarios-form">
    <div class="col-lg-5">
        <h2>Eliminar o expulsar un usuario</h2>

        <div>
            <?php $form = ActiveForm::begin(['id' => 'usuarios-form']); ?>
                <?= $form->field($model, 'usuario_id')->dropDownList($usuarios) ?>
                <?= $form->field($model, 'accion')->dropDownList([
                    'eliminar' => 'Eliminar',
                    'expulsar' => 'Expulsar',
                    'quitar-expulsion' => 'Quitar expulsión'
                ], ['value' => \Yii::$app->request->get('accion')]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Hacer acción a usuario', ['class' => 'btn btn-success']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
