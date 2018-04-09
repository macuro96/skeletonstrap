<?php
return [
    'aliases' => [
        '@bower'        => '@vendor/bower-asset',
        '@npm'          => '@vendor/npm-asset',
        '@commonViews'  => '@common/components/views'
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
    ],
];
