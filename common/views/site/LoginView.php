<?php

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use common\components\CheckEnd;
?>

<div class="site-login">
    <div class="row">
        <div class="col-lg-offset-4 col-lg-4">
            <h1><?= Html::encode($this->title) ?></h1>

            <p>Para solicitar una cuenta primero debe aceptar las normas del equipo y rellenar un formulario, que se puede acceder a través de este <?= Html::a('enlace', '/site/unete') ?>.</p>

            <div class="row">
                <div class="col-lg-12">
                    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'password')->passwordInput() ?>

                        <?php if (CheckEnd::isFrontEnd()) : ?>
                            <?= Html::a('Soy un administrador', '/admin/site/login') ?>
                        <?php else : ?>
                            <?= Html::a('Soy un jugador', '/site/login') ?>
                        <?php endif; ?>

                        <?= $form->field($model, 'rememberMe')->checkbox() ?>

                        <div class="form-group">
                            <?= Html::submitButton('Entrar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
