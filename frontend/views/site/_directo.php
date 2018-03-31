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
<div class="row div-directo">
    <div class="col-md-12">
        <div class="row seccion">
            <div class="col-md-offset-3 col-md-6">
                <div class="img-seccion">
                    <?= Html::img(CheckEnd::rutaRelativa() . 'images/logo.png', ['class' => 'img-responsive']) ?>
                </div>

                <p class="titulo-seccion">
                    <h2>Trappero, bienvenido a la web del equipo <b>"Skeleton's Trap"</b></h2>
                </p>

                <p>
                    Somos un equipo pequeño y humilde pero con muchas ganas de conseguir grandes cosas.
                </p>
            </div>
        </div>

        <!--
        <div class="row seccion">
            <div class="col-md-offset-3 col-md-6">
                <p>
                    Buscamos nuevos miembros que quieran formar parte de Skeleton's Trap, si quieres ser uno de ellos
                    puedes leer las normas, y contactar con nosotros a través de un formulario.
                </p>
                <p>
                    Para acceder al formulario puedes hacerlo a través de este enlace o en el apartado 'Únete/Lucha' del menú principal.
                </p>
            </div>
        </div>
        -->
        <?= MostrarIndex::mejoresPartidas('8NTmRZP42xSlkg') ?>
        <div class="row etiquetas">
            <div class="col-md-offset-4 col-md-4">
                Últimas noticias
            </div>
        </div>
        <div class="row noticias">
            <div class="col-md-offset-3 col-md-6">
                <?= RedesSociales::timelineTwitter() ?>
            </div>
        </div>

    </div>
</div>
