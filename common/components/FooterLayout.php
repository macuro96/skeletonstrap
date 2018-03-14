<?php

namespace common\components;

use Yii;

use yii\helpers\Html;

class FooterLayout
{
    public static function mostrar()
    {
        ?>
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <p class="copy">&copy; <?= Html::encode(Yii::$app->name) ?> <?= 2018 ?></p>
                        <p class="correo"><?= Html::encode(Yii::$app->params['supportEmail']) ?></p>
                    </div>
                    <div class="col-md-4 logo">
                        <?= Html::img(CheckEnd::rutaRelativa() . 'images/logo.png', ['class' => 'img-responsive']) ?>
                    </div>
                    <div class="col-md-4 redes">
                        <p><a href="https://twitter.com/SkeletonsTrapCR?ref_src=twsrc%5Etfw" class="twitter-follow-button" data-size="large" data-lang="es" data-show-count="false">Follow @SkeletonsTrapCR</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></p>
                    </div>
                </div>
            </div>
        </footer>
        <?php
    }
}
