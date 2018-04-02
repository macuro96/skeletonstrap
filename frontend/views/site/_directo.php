<?php

use yii\web\View;

use yii\helpers\Html;

use common\components\CheckEnd;
use common\components\MostrarIndex;
use common\components\RedesSociales;

$this->registerCssFile('css/_directo.css');
$this->registerJsFile('js/_directo.js', ['position' => View::POS_END,
                                         'depends'  => [\yii\web\JqueryAsset::className()]]);
?>
<div class="row cabecera-inicio">
    <div class="col-lg-offset-3 col-lg-6 col-md-12">
        <div class="img-centered logo-inicio">
            <?= Html::img(CheckEnd::rutaRelativa() . 'images/logo.png', ['class' => 'img-responsive']) ?>
        </div>

        <p class="titulo-inicio">
            Trappero, bienvenido a la web del equipo <b>"Skeleton's Trap"</b>
        </p>

        <p>
            Ésta es una web en la que encontrarás información sobre nosotros: integrantes del equipo, estadísticas, torneos y poder unirte o luchar contra nosotros...
        </p>
    </div>
</div>
<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Sobre nosotros</h2></div>

        <div class="contenido-seccion">
            <div class="row">
                <div class="col-lg-4">
                    <div class="img-centered">
                        <?= Html::img(CheckEnd::rutaRelativa() . 'images/clash-royale-logo.png', ['class' => 'img-responsive']) ?>
                    </div>
                </div>
                <div class="col-lg-8">
                    <p>
                        Sed vel est quis risus imperdiet eleifend. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In et mi rutrum, laoreet lorem id, lobortis nunc. Sed vehicula libero quam, vel porta sapien tincidunt vitae. Sed congue ligula ac neque suscipit tempor non placerat diam.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
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
                                    <?= Html::img(CheckEnd::rutaRelativa() . 'images/logo.png', ['class' => 'img-responsive']) ?>
                                </td>
                                <td>Skeleton's Trap</td>
                                <td>Stadium Trap</td>
                                <td class="marcador">0 - 0</td>
                            </tr>
                            <tr>
                                <td class="hidden-logo">
                                    <?= Html::img(CheckEnd::rutaRelativa() . 'images/Logo4K1.png', ['class' => 'img-responsive']) ?>
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
<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Mejores partidas</h2></div>

        <div class="contenido-seccion">
            <div class="row">
                <div class="col-lg-12 centrar">
                    <?= MostrarIndex::mejoresPartidas('8NTmRZP42xSlkg') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 centrar">
                    <h2><?= Html::a('¿Te atréves a jugar <b>contra nosotros</b>?', ['/site/lucha'], ['class' => 'a-none']) ?></h2>
                    <div class="redes-sociales">
                        <?= RedesSociales::twitter('https://skeletons-trap.herokuapp.com/ ¿Te atréves a jugar contra nosotros? Somos Skeleton\'s Trap') ?>
                        <?= RedesSociales::whatsapp('https://skeletons-trap.herokuapp.com/ ¿Te atréves a jugar contra nosotros? Somos Skeleton\'s Trap') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
