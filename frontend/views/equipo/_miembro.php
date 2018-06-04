<?php

use common\components\Recursos;
?>
<div class="col-md-4 col-sm-6">
    <div class="jugador" data-jugador="<?= $model->jugadores->tag ?>">
        <?= Recursos::imageCommon('perfil.png', ['class' => 'img-perfil img-resposive']) ?>
        <div class="load">
            <?= $this->render('_jugador', [
                'model' => $model
            ]); ?>
        </div>
    </div>
</div>
