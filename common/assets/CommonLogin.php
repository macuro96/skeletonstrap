<?php

namespace common\assets;

use yii\web\AssetBundle;

class CommonLogin extends AssetBundle
{
    public $sourcePath = '@common/scripts/login';
    public $css = [
        'login.css'
    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
