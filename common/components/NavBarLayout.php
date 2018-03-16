<?php

namespace common\components;

class NavBarLayout
{
    public static function brandLabel()
    {
        return \Yii::$app->name;
    }

    public static function loginButton()
    {
        return  [
                    'label' => 'Login' . ' <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>',
                    'url' => ['/site/login']
                ];
    }
}