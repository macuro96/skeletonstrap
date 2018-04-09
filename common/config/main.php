<?php
return [
    'aliases' => [
        '@bower'        => '@vendor/bower-asset',
        '@npm'          => '@vendor/npm-asset',
        '@commonViews'  => '@common/views'
    ],
    'language' => 'es-ES',
    'name'     => 'Skeleton\'s Trap',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter' => [
            'timeZone' => 'Europe/Madrid',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'defaultRoute' => '/site/index',
            ],
        ],
    ],
];
