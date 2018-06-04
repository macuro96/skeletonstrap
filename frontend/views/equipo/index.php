<?php

use yii\helpers\Html;

use common\components\Recursos;
use common\components\RegisterThisCss;

/* @var $this yii\web\View */

$this->title = 'Equipo';

$js = <<<EOT
    $('.contenido-seccion').on('click', '.load .btn-actualizar-miembro', function(e) {
        e.preventDefault();

        var dataTag = $(this).closest('.jugador').data('jugador');
        var urlTo   = $(this).attr('href');

        var dataDiv = $(this).closest('.load');

        $(this).attr('disabled', 'disabled');

        $.ajax({
            url: urlTo,
            type: 'POST',
            data: {
                tag: dataTag
            },
            success: function (data) {
                $(dataDiv).html(data);
                $(this).removeAttr('disabled');
            },
            error: function (error) {
                $(this).removeAttr('disabled');
                alert('Ha ocurrido un error inesperado en la carga de datos para ese jugador.');
            }
        });
    });
EOT;

$this->registerJs($js);

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
            Aquí encontrarás información sobre el equipo y sus miembros: estadísticas del juego, requisitos del equipo, etc.
        </p>
    </div>
</div>
<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Información</h2></div>

        <div class="contenido-seccion">
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <p>
                        <div class="div-logo img-centered">
                            <?= Recursos::imageCommon('logo.png', ['class' => 'img-responsive']) ?>
                        </div>
                    </p>
                    <div class="info-equipo">
                        <p>
                            <?= Html::encode($clan->descripcion) ?>
                        </p>
                        <p>
                            TAG: <b><?= Html::encode($clan->tag) ?></b>
                        </p>
                        <p>
                            Número de miembros: <b><?= Html::encode($clan->numero_miembros) ?></b>
                        </p>

                        <h2><?= Recursos::imageCommon('trophy.png', ['class' => 'img-icon img-resposive']) ?> Copas</h2>
                        <p>
                            <u>Mínimo de copas para entrar en el equipo: <b><?= Html::encode($clan->copas_requeridas) ?></b></u>
                        </p>
                        <p>
                            Copas totales: <b><?= Html::encode($clan->copas) ?></b>
                        </p>

                        <h2><?= Recursos::imageCommon('battle.png', ['class' => 'img-icon img-resposive']) ?> Donaciones</h2>
                        <p>
                            Donaciones por semana: <b><?= Html::encode($clan->donaciones_semana) ?></b>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Miembros (<?= count($jugadores) ?>)</h2></div>

        <div class="contenido-seccion">
            <?php foreach ($jugadores as $jugador) : ?>
                <?= $this->render('_miembro', [
                    'model' => $jugador
                ]); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
