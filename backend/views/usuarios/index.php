<?php

use yii\helpers\Html;

use common\components\RegisterThisCss;

/* @var $this     yii\web\View */
/* @var $usuarios common\models\Usuarios */
/* @var $usuariosPendientes common\models\Usuarios */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;

RegisterThisCss::register($this);
?>
<div class="row">
    <div class="col-md-3 modulo solicitudes-entrar">
        <div class="row titulo-cabecera centrar">
            <div class="col-md-12">
                <h3>Solicitudes para entrar en el equipo (<numero-solicitudes><?= Html::encode(count($usuariosPendientes)) ?></numero-solicitudes>)</h3>
            </div>
        </div>
        <div class="row contenido">
            <div class="col-md-12">
                <div class="acciones centrar">
                    <?= Html::a('Invitar a un jugador', ['invitar'], ['class' => 'btn btn-success btn-lg']) ?>
                </div>
                <div id="usuarios-solicitudes">
                    <?php foreach ($usuariosPendientes as $us) : ?>
                        <?= $this->render('_usuario', [
                            'model' => $us
                        ]); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
