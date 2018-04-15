<?php

use yii\helpers\Html;

use backend\components\Ruta;

/* @var $this     yii\web\View */
/* @var $usuarios common\models\Usuarios */
/* @var $usuariosNoActivos common\models\Usuarios */

$this->title = 'Usuarios';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile(Ruta::to('css/usuarios/index.css'));
?>

<div class="row seccion solicitudes-entrar">
    <div class="col-lg-12">
        <div class="titulo-seccion"><h2>Solicitudes para entrar en el equipo (<numero-solicitudes><?= Html::encode('1') ?></numero-solicitudes>)</h2></div>

        <div class="contenido-seccion">
            <div class="acciones centrar">
                <?= Html::a('Nueva invitación', ['invitar'], ['class' => 'btn btn-success btn-lg']) ?>
            </div>
            <?php foreach ($usuariosNoActivos as $us) : ?>
                <?= $this->render('_usuario', [
                    'model' => $us
                ]); ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
