<?php

/* @var $this yii\web\View */
/* @var $detect Detection\MobileDetect */

use yii\helpers\Url;

use common\components\Recursos;
use common\components\RedesSociales;
use common\components\RegisterThisJs;
use common\components\RegisterThisCss;

$this->title = 'Inicio';

RegisterThisCss::register($this);
RegisterThisJs::register($this);

$urlActionAccionActual = Url::to(['site/accion-actual']);
$urlActionDatosDirecto = Url::to(['site/datos-directo']);

$js = <<<EOT
    var timeDirecto = null;
    var timeAccion  = null;

    function empezarComprobarAccion() {
        if (timeAccion == null) {
            timeAccion = setInterval(ajaxAccion, 5000);
        }
    }

    function pararComprobarAccion() {
        clearInterval(timeAccion);
    }

    function empezarDirecto() {
        if (timeDirecto == null) {
            timeDirecto = setInterval(ajaxDirecto, 5000);
        }
    }

    function pararDirecto() {
        clearInterval(timeDirecto);
    }

    function ajaxAccion () {
        $.ajax({
            url: '$urlActionAccionActual',
            type: 'POST',
            success: function (data) {
                if (data.accion == 'd' || data.accion == 'p') {
                    if ($('.directo').length == 0) {
                        location.reload();
                    } else {
                        if (data.accion == 'd') {
                            empezarDirecto();
                        } else if (data.accion == 'p') {

                        }
                    }
                } else {
                    empezarComprobarAccion();
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    function ajaxDirecto() {
        $.ajax({
            url: '$urlActionDatosDirecto',
            type: 'POST',
            success: function (data) {
                if (data.activo == true) {
                    var divDirecto = $('.directo');
                    divDirecto.find('titulo').html(data.titulo);

                    if (data.subtitulo) {
                        divDirecto.find('subtitulo').html(': ' + data.subtitulo);
                    } else {
                        divDirecto.find('subtitulo').html('');
                    }

                    divDirecto.find('.resp-sharing-button--twitter').closest('.resp-sharing-button__link').attr('href', 'https://twitter.com/intent/tweet/?text=' + data.msgTwitter);
                    divDirecto.find('.resp-sharing-button--whatsapp').closest('.resp-sharing-button__link').attr('href', 'whatsapp://send?text=' + data.msgWhatsapp);

                    divDirecto.find('.equipo marcador').html(data.marcadorPropio);
                    divDirecto.find('.equipo-enemigo marcador').html(data.marcadorOponente);

                    divDirecto.find('.logo-enemigo img').attr('src', data.logoOponente);
                } else {
                    location.reload();
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

    ajaxAccion();
EOT;

$this->registerJs($js);
?>
<div class="row cabecera-inicio" itemscope itemtype="http://schema.org/SportsTeam">
    <div class="col-lg-offset-3 col-lg-6">
        <div itemprop="logo" class="img-centered logo-inicio">
            <?= Recursos::imageCommon('logo.png') ?>
        </div>

        <p class="titulo-inicio">
            Trappero, bienvenido a la web del equipo <b itemprop="name">"Skeleton's Trap"</b>
        </p>

        <p itemprop="description">
            Ésta es una web en la que encontrarás información sobre: integrantes del equipo, estadísticas, torneos. También podrás contactarnos para poder unirte o luchar contra nosotros...
        </p>
    </div>
</div>

<evento-partida>
    <?= $eventoPartida ?>
</evento-partida>

<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Sobre nosotros</h2></div>

        <div class="contenido-seccion">
            <div class="row">
                <div class="col-lg-4">
                    <div class="img-centered">
                        <?= Recursos::imageCommon('clash-royale-logo.png') ?>
                    </div>
                </div>
                <div class="col-lg-8">
                    <p>
                        Somos un equipo competitivo de Clash Royale, empezamos nuestros pasos en el año 2017 llenos de ilusión
                        y con ganas de crecer. Durante todo este tiempo hemos ido creciendo poco a poco y manteniendo un buen nivel competitivo
                        en las batallas a las que nos hemos enfrentado.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!--
<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion" id="ultimos-encuentros">
            <h2>Últimos encuentros <span class="glyphicon glyphicon-refresh btn" aria-hidden="true"></span></h2>
            <h6>Última actualización hace: <tiempo>1 min</tiempo>...</h6>
        </div>

        <div class="contenido-seccion">
            <div class="row encuentro">
                <div class="col-lg-12">
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th class="hidden-logo">
                                    Logo
                                </th>
                                <th>
                                    Equipo
                                </th>
                                <th>
                                    Estadio
                                </th>
                                <th>
                                    Marcador
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="hidden-logo">
                                    <?= Recursos::imageCommon('logo.png') ?>
                                </td>
                                <td>Skeleton's Trap</td>
                                <td>Stadium Trap</td>
                                <td class="marcador">0 - 0</td>
                            </tr>
                            <tr>
                                <td class="hidden-logo">
                                    <?= Recursos::imageCommon('Logo4K1.png') ?>
                                </td>
                                <td>TeamQueso</td>
                                <td>Stadium Trap</td>
                                <td class="marcador">3 - 0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
-->
<?= $this->render('_mejoresPartidas', [
    'config' => $config,
    'detect' => $detect
]) ?>
<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Novedades</h2></div>

        <div class="contenido-seccion">
            <div class="row">
                <div class="col-lg-12 centrar">
                    <?= RedesSociales::timelineTwitter() ?>
                </div>
            </div>
        </div>
    </div>
</div>
