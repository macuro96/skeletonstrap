<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\assets\CommonAsset;

use common\components\FooterLayout;
use common\components\NavBarLayout;

use backend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="container-fluid">
    <?= Alert::widget() ?>
    <div class="contenedor-menu">
        <div class="row">
            <div class="col-md-2 menu">
                <div class="row titulo">
                    <div class="col-md-12">
                        Skeleton's Trap
                    </div>
                </div>
                <div class="row opcion-row active">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-chevron-right"></span>Inicio
                    </div>
                </div>
                <div class="row opcion-row">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-chevron-right"></span>Solicitudes para entrar
                    </div>
                </div>
                <div class="row opcion-row">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-chevron-right"></span>Calendario
                    </div>
                </div>
                <div class="row opcion-row">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-chevron-right"></span>Torneos
                    </div>
                </div>
                <div class="row opcion-row salir">
                    <div class="col-md-12">
                        <span class="glyphicon glyphicon-chevron-right"></span>Salir
                    </div>
                </div>
            </div>
            <div class="col-md-9 contenido">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
