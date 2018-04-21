<?php
/* @var $this yii\web\View */
/* @var $usuario common\models\Usuarios */
/* @var $model frontend\models\VerificarForm */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\components\RegisterThisCss;

$this->title = 'Verificar usuario nuevo';
$this->params['breadcrumbs'][] = $this->title;

RegisterThisCss::register($this);
?>
<div class="site-login">
    <div class="row">
        <div class="col-lg-offset-3 col-lg-6 contenedor">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>Bienvenido al equipo <b><?= $usuario->nombre ?></b>, rellena los siguientes datos para poder entrar en la aplicación:</p>

            <div class="row">
                <div class="col-lg-12">
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                        <?= $form->field($model, 'password')->passwordInput() ?>

                        <?= $form->field($model, 'password_repeat')->passwordInput() ?>

                        <div class="form-group">
                            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
