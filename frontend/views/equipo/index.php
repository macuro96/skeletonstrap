<?php

use common\components\Recursos;
use common\components\RegisterThisCss;

/* @var $this yii\web\View */

$this->title = 'Equipo';

RegisterThisCss::register($this);
?>
<div class="row cabecera-inicio">
    <div class="col-lg-offset-3 col-lg-6">
        <div class="img-centered logo-inicio">
            <?= Recursos::imageCommon('equipo.png') ?>
        </div>

        <p class="titulo-inicio">
            Nuestro equipo en <b>Skeleton's Trap</b>
        </p>

        <p>
            Aquí encontrarás información sobre los miembros del equipo: estadísticas del juego, información personal, etc.
        </p>
    </div>
</div>
<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Miembros</h2></div>

        <div class="contenido-seccion">
            <?php foreach ($jugadores as $jugador) : ?>
                <?= $this->render('_miembro', [
                    'model' => $jugador
                ]); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
