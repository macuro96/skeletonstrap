<?php

namespace common\components;

use yii\helpers\Html;

class Recursos
{
    public static function imageCommon($nombre, $options = ['class' => 'img-responsive'])
    {
        return Html::img(CheckEnd::rutaRelativa() . 'images/' . $nombre, $options);
    }


    public static function image($nombre, $options = ['class' => 'img-responsive'])
    {
        $lugar = CheckEnd::isBackEnd() ? '/admin/' : '';
        
        return Html::img($lugar . 'images/' . $nombre, $options);
    }
}
