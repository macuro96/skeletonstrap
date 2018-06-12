<?php

/* @var $this yii\web\View */
/* @var $model common\models\Calendario */

$this->title = 'Crear un evento';
?>
<h2>Crear un evento</h2>

<?= $this->render('_form', [
    'model' => $model,
    'etiquetas' => $etiquetas,
    'rolesVisibilidad' => $rolesVisibilidad,
]) ?>
