<?php

namespace common\assets;

use yii\web\AssetBundle;

class CommonFormNuevoUsuario extends AssetBundle
{
    public $sourcePath = '@common/scripts/form-nuevo-usuario';
    public $css = [
        'form-nuevo-usuario.css'
    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
