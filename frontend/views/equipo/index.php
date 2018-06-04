<?php

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
            Aquí encontrarás información sobre los miembros del equipo: estadísticas del juego, información personal, etc.
        </p>
    </div>
</div>
<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Información</h2></div>

        <div class="contenido-seccion">
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
