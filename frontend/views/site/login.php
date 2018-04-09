<?php

/* @var $this yii\web\View */
/* @var $model \common\models\LoginForm */

use common\assets\CommonLogin;

use common\components\RenderCommonView;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

CommonLogin::register($this);
?>
<?= RenderCommonView::render($this, [
    'model' => $model
]) ?>
