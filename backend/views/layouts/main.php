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
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#964545">        
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
                        <div class="row opcion-row ' . (($nombreControlador == 'site' && ($nombreAccion == 'index' || $nombreAccion == 'administrar-cuentas' || $nombreAccion == 'web')) ? 'active' : '') . '">
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
                        <div class="row opcion-row salir">
                            <div class="col-md-12">
                                <span class="glyphicon glyphicon-chevron-right"></span>Salir (' . \Yii::$app->user->identity->nombre . ')' .
                            '</div>
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
