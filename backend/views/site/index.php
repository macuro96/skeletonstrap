<?php

use yii\helpers\Html;

use common\components\RedesSociales;
use common\components\RegisterThisCss;

/* @var $this yii\web\View */

$this->title = 'General - Panel de administración';

if ($configuracionAcciones['accion'] == 'd') {
    $msgAccion = 'DIRECTO';
} elseif ($configuracionAcciones['accion'] == 'p') {
    $msgAccion = 'PRÓXIMA PARTIDA';
} else {
    $msgAccion = 'NINGUNO';
}

RegisterThisCss::register($this);
?>
<div class="row">
    <div class="col-md-3 modulo">
        <div class="row titulo-cabecera centrar">
            <div class="col-md-12">
                <h3>Navegación</h3>
            </div>
        </div>
        <div class="row contenido">
            <div class="col-md-12">
                <div class="acciones centrar">
                    <?= Html::a('Usuarios', ['usuarios/index'], ['class' => 'btn btn-primary btn-lg']) ?>
                    <?= Html::a('Calendario', ['site/calendario/index'], ['class' => 'btn btn-warning btn-lg']) ?>
                    <?= Html::a('Torneos', ['torneos/index'], ['class' => 'btn btn-primary btn-lg']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 modulo">
        <div class="row titulo-cabecera centrar">
            <div class="col-md-12">
                <h3>Administrar cuentas</h3>
            </div>
        </div>
        <div class="row contenido">
            <div class="col-md-12">
                <div class="acciones centrar">
                    <?= Html::a('Configuración', ['administrar-cuentas'], ['class' => 'btn btn-primary btn-lg']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 modulo">
        <div class="row titulo-cabecera centrar">
            <div class="col-md-12">
                <h3>Configuración</h3>
            </div>
        </div>
        <div class="row contenido">
            <div class="col-md-12">
                <div class="acciones centrar">
                    <?= Html::a('Directo', ['directo'], ['class' => 'btn btn-danger btn-lg btn-extenso']) ?>
                </div>
                <div class="acciones centrar">
                    <?= Html::a('Programar próx partida', ['proxima-partida'], ['class' => 'btn btn-warning btn-lg btn-extenso']) ?>
                </div>
                <div class="acciones centrar">
                    <?= Html::a('Mejores partidas', ['mejores-partidas'], ['class' => 'btn btn-primary btn-lg btn-extenso']) ?>
                </div>
                <div class="acciones centrar">
                    <?= Html::a('Contraseña admin', ['cambiar-contrasena'], ['class' => 'btn btn-primary btn-lg btn-extenso']) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 modulo">
        <div class="row titulo-cabecera centrar">
            <div class="col-md-12">
                <h3>Acciones</h3>
            </div>
        </div>
        <div class="row contenido">
            <div class="col-md-12">
                <p class="centrar">
                    Activado: <b><?= $msgAccion ?></b>
                </p>
                <?php if ($configuracionAcciones['accion']) : ?>
                    <div class="acciones centrar">
                        <?= Html::a('Desactivar', ['accion', 'activar' => 'n'], ['class' => 'btn btn-primary btn-lg btn-extenso']) ?>
                    </div>
                <?php endif; ?>
                <?php if ($configuracionAcciones['accion'] != 'd') : ?>
                    <div class="acciones centrar">
                        <?= Html::a('Activar directo', ['accion', 'activar' => 'd'], ['class' => 'btn btn-danger btn-lg btn-extenso']) ?>
                    </div>
                <?php endif; ?>
                <?php if ($configuracionAcciones['accion'] != 'p') : ?>
                    <div class="acciones centrar">
                        <?= Html::a('Activar próx partida', ['accion', 'activar' => 'p'], ['class' => 'btn btn-warning btn-lg btn-extenso']) ?>
                    </div>
                <?php endif; ?>
                <div class="acciones centrar">
                    <?= RedesSociales::twitter($msgUnete['twitter'], 'Únete') ?>
                    <?= RedesSociales::whatsapp($detect, $msgUnete['whatsapp'], 'Únete') ?>
                </div>
            </div>
        </div>
    </div>
</div>
