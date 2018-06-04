<?php

use yii\helpers\Url;
use yii\helpers\Html;

use common\models\Jugadores;
use common\models\ConfigTiempoActualizado;

use common\components\Recursos;
use common\components\RegisterThisCss;

$this->title = 'Mi perfil';

$usuario = \Yii::$app->user->identity;

if ($usuario && ConfigTiempoActualizado::ultimaActualizacionJugador($usuario->jugadores->tag)) {
    Jugadores::findAPI('jugador', [
        'tag' => [
            $usuario->jugadores->tag
        ]
    ]);
}

$this->registerCssFile('/css/equipo/index.css');

$url = Url::to(['site/cambiar-info-perfil']);

$js = <<<EOT
    var ventanaCerrada = false;

    $('.glyphicon-pencil').on('click', function() {
        var ventana = window.open("$url", 'Actualizar mi perfil', 'scrollbars=1,resizable=1,width=1000,height=580,left=0,top=0');

        ventana.onunload = function (){
            if (ventanaCerrada) {
                location.reload();
            } else {
                ventanaCerrada = true;
            }
        }
    })
EOT;

$this->registerJs($js);

RegisterThisCss::register($this);
?>
<div class="row cabecera-inicio">
    <div class="col-lg-offset-3 col-lg-6">
        <div class="img-centered logo-inicio">
            <?= Recursos::imageCommon('perfil.png', ['class' => 'mi-perfil']) ?>
        </div>

        <p class="titulo-inicio">
            Mi perfil
        </p>

        <p>
            Mi perfil de usuario en la aplicación.
        </p>
    </div>
</div>
<div class="row seccion">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Información <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></h2></div>

        <div class="contenido-seccion">
            <div class="row">
                <div class="col-md-offset-3 col-md-6">
                    <div class="jugador">
                        <?= Recursos::imageCommon('perfil.png', ['class' => 'img-perfil img-resposive']) ?>
                        <span class="nombre"><?= Html::encode($usuario->jugadores->nombre) ?></span> <span class="rol">(<?= Html::encode($usuario->jugadores->clan_rol) ?>)</span>
                        <div class="datos">
                            <div class="seccion-datos">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="titulo-datos">Datos de usuario</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 nombre-dato">
                                        Nacionalidad
                                    </div>
                                    <div class="col-md-6 valor-dato">
                                        <?= Html::encode($usuario->nacionalidad->nombre) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 nombre-dato">
                                        Nombre de usuario
                                    </div>
                                    <div class="col-md-6 valor-dato">
                                        <?= Html::encode($usuario->nombre) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 nombre-dato">
                                        <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span> Correo
                                    </div>
                                    <div class="col-md-6 valor-dato">
                                        <?= Html::encode($usuario->correo) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="seccion-datos">
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="titulo-datos">Datos de jugador</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 nombre-dato">
                                        TAG
                                    </div>
                                    <div class="col-md-6 valor-dato">
                                        <?= Html::encode($usuario->jugadores->tag) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 nombre-dato">
                                        Nivel
                                    </div>
                                    <div class="col-md-6 valor-dato">
                                        <?= Html::encode($usuario->jugadores->nivel) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 nombre-dato">
                                        <?= Recursos::imageCommon('trophy.png', ['class' => 'img-icon img-resposive']) ?> Copas
                                    </div>
                                    <div class="col-md-6 valor-dato">
                                        <?= Html::encode($usuario->jugadores->copas) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 nombre-dato">
                                        <?= Recursos::imageCommon('battle.png', ['class' => 'img-icon img-resposive']) ?> Victorias
                                    </div>
                                    <div class="col-md-6 valor-dato">
                                        <?= Html::encode($usuario->jugadores->victorias) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 nombre-dato">
                                        <?= Recursos::imageCommon('ligas/' . $usuario->jugadores->liga->icono . '.png', ['class' => 'img-icon img-resposive']) ?> Arena
                                    </div>
                                    <div class="col-md-6 valor-dato">
                                        <?= Html::encode($usuario->jugadores->liga->nombre) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 nombre-dato">
                                        Partidas totales
                                    </div>
                                    <div class="col-md-6 valor-dato">
                                        <?= Html::encode($usuario->jugadores->partidas_totales) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 nombre-dato">
                                        Derrotas
                                    </div>
                                    <div class="col-md-6 valor-dato">
                                        <?= Html::encode($usuario->jugadores->derrotas) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 nombre-dato">
                                        Cartas descubiertas
                                    </div>
                                    <div class="col-md-6 valor-dato">
                                        <?= Html::encode($usuario->jugadores->cartas_descubiertas) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
