<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=localhost;dbname=skeletonstrap',
            'username' => 'skeletonstrap',
            'password' => 'skeletonstrap',
            'charset' => 'utf8',
        ],
        'assetManager' => [
            'forceCopy' => true
        ]
    ],
];
