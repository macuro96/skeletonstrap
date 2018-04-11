<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use common\assets\CommonLogin;

use common\components\RenderCommonView;

$this->title = 'Login de cuenta con privilegios';
$this->params['breadcrumbs'][] = $this->title;

CommonLogin::register($this);
?>
<?= RenderCommonView::render($this, [
    'model' => $model
]) ?>
