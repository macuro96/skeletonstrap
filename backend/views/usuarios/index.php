<?php

use yii\helpers\Html;

use backend\components\Ruta;

/* @var $this     yii\web\View */
/* @var $usuarios common\models\Usuarios */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(Ruta::to('css/usuarios/index.css'));
?>

<div class="row seccion solicitudes-entrar">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Solicitudes para entrar en el equipo (<numero-solicitudes><?= Html::encode('1') ?></numero-solicitudes>)</h2></div>

        <div class="contenido-seccion">
            <div class="acciones centrar">
                <?= Html::a('Nueva invitación', ['invitar'], ['class' => 'btn btn-success btn-lg']) ?>
            </div>

            <div class="usuario" data-usuario="1">
                <div class="row">
                    <div class="col-lg-offset-3 col-md-offset-1 col-lg-1 col-md-1 img-centered">
                        <?= Html::img(Ruta::to('images/perfil.png'), ['class' => 'img-responsive img-perfil']) ?>
                    </div>
                    <div class="col-lg-3 col-md-7 datos">
                        <h5>Usuario:</h5>
                        <p>
                            <?= Html::encode('Manuel') ?>
                        </p>
                        <h5>Correo:</h5>
                        <p>
                            <?= Html::encode('manuelcuevasrod96ssssss@gmaissssssssssssssssssssssssssssssssssl.com') ?>
                        </p>
                    </div>
                    <div class="col-lg-2 col-md-2 botones">
                        <?= Html::a('Aceptar', ['aceptar'], ['class' => 'btn btn-success btn-lg']) ?>
                        <?= Html::a('Cancelar', ['cancelar'], ['class' => 'btn btn-danger btn-lg']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
