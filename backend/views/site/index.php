<?php

use yii\helpers\Html;

use common\components\RegisterThisCss;

/* @var $this yii\web\View */

$this->title = 'Inicio - Panel de administración';

RegisterThisCss::register($this);
?>
<div class="row">
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
