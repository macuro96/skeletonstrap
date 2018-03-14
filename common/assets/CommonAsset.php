<?php

namespace common\assets;

use yii\web\AssetBundle;

class CommonAsset extends AssetBundle
{
    public $sourcePath = '@common/scripts';
    public $css = [
        'site.css'
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
