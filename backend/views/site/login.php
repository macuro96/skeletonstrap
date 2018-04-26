<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\LoginForm */

use yii\helpers\Html;

use yii\widgets\ActiveForm;

use common\components\RegisterThisCss;

$this->title = 'Inicio de sesión';
RegisterThisCss::register($this);
?>
<div class="contenedor-login">
    <div class="row">
        <div class="col-md-offset-4 col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <h1><?= Html::encode($this->title) ?></h1>
                    <p>Este inicio de sesión contiene privilegios de administrador. Solo podrán tener acceso aquellos miembros que tengan dicho permiso.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                        <?= $form->field($model, 'usuario')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'password')->passwordInput() ?>

                        <?= $form->field($model, 'rememberMe')->checkbox() ?>

                        <div class="form-group">
                            <?= Html::submitButton('Entrar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                            <?= Html::a('Soy un jugador', '/site/login', ['class' => 'btn btn-danger']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
