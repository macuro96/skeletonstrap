<?php

$url      = getenv('DATABASE_URL');
$aDatos   = parse_url($url);

$host     = $aDatos['host'];
$port     = $aDatos['port'];
$dbname   = substr($aDatos['path'], 1);
$username = $aDatos['user'];
$password = $aDatos['pass'];
$extra = [
    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
    'on afterOpen' => function ($event) {
        // $event->sender refers to the DB connection
        $event->sender->createCommand("SET intervalstyle = 'iso_8601'")->execute();
    },
];

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => "pgsql:host=$host;port=$port;dbname=$dbname",
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
        ] + $extra,
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
