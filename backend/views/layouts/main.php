<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

use common\assets\CommonAsset;

use backend\assets\AppAsset;
use common\widgets\Alert;

CommonAsset::register($this);
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
<?php
$tmpControlador = $this->context->module->controller;

$nombreControlador = $tmpControlador->id;
$nombreAccion      = $tmpControlador->action->id;

$bSiteLogin = ($nombreControlador == 'site' && $nombreAccion == 'login');
?>
<div class="container-fluid">
    <?php if ($bSiteLogin) : ?>
        <?= $content ?>
    <?php else : ?>
        <div class="contenedor-menu navbar-fija">
            <div class="row">
                <div class="col-md-2 menu">
                    <div class="row titulo">
                        <div class="col-md-12">
                            Skeleton's Trap
                        </div>
                    </div>
                    <div class="opciones">
                        <?= Html::a('
                        <div class="row opcion-row ' . (($nombreControlador == 'site' && ($nombreAccion == 'index' || $nombreAccion == 'administrar-cuentas')) ? 'active' : '') . '">
                            <div class="col-md-12">
                                <span class="glyphicon glyphicon-chevron-right"></span>General
                            </div>
                        </div>
                        ', ['site/index']);
                        ?>
                        <?= Html::a('
                        <div class="row opcion-row ' . (($nombreControlador == 'usuarios') ? 'active' : '') . '">
                            <div class="col-md-12">
                                <span class="glyphicon glyphicon-chevron-right"></span>Usuarios
                            </div>
                        </div>
                        ', ['usuarios/index']);
                        ?>
                        <?= Html::a('
                        <div class="row opcion-row ' . (($nombreControlador == 'site' && $nombreAccion == 'calendario') ? 'active' : '') . '">
                            <div class="col-md-12">
                                <span class="glyphicon glyphicon-chevron-right"></span>Calendario
                            </div>
                        </div>
                        ', ['site/calendario']); ?>
                        <?= Html::a('
                        <div class="row opcion-row ' . (($nombreControlador == 'torneos') ? 'active' : '') . '">
                            <div class="col-md-12">
                                <span class="glyphicon glyphicon-chevron-right"></span>Torneos
                            </div>
                        </div>
                        ', ['torneos/index']);
                        ?>
                        <?= Html::a('
                        <div class="row opcion-row salir">
                            <div class="col-md-12">
                                <span class="glyphicon glyphicon-chevron-right"></span>Salir
                            </div>
                        </div>
                        ', ['site/logout'], [
                            'data' => [
                                'method' => 'post'
                            ]
                        ]);
                        ?>
                    </div>
                    <div class="row visible-sm visible-xs expandir">
                        <div class="col-md-12 centrar">
                            <span aria-label="true" class="glyphicon glyphicon-chevron-down"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row contenedor-menu-contenido">
            <div class="col-md-offset-2 col-md-10">
                <?= Alert::widget() ?>
                <?= $content ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
