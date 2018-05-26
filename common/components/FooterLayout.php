<?php

namespace common\components;

use Yii;

use yii\helpers\Html;

/**
 * Componente común de pie de layout main
 */
class FooterLayout
{
    /**
     * Mostrar pie
     */
    public static function mostrar()
    {
        ?>
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <p class="copy">&copy; <?= Html::encode(Yii::$app->name) ?> <?= 2018 ?></p>
                        <a href="mailto:<?= Html::encode(Yii::$app->params['supportEmail']) ?>" class="correo"><p><span class="glyphicon glyphicon-envelope"></span><?= Html::encode(Yii::$app->params['supportEmail']) ?></p></a>
                    </div>
                    <div class="col-md-4 logo">
                        <a href="<?= Yii::$app->homeUrl ?>"><?= Recursos::imageCommon('logo.png') ?></a>
                    </div>
                    <div class="col-md-4 redes">
                        <p>Síguenos</p>
                        <a href="https://twitter.com/SkeletonsTrapCR" class="twitter"><i class="fa fa-twitter"></i></a>
                        <a href="https://www.twitch.tv/skeletonstraptv" class="twitch"><i class="fa fa-twitch"></i></a>
                    </div>
                </div>
            </div>
        </footer>
        <?php
    }
}
