<?php

return yii\helpers\ArrayHelper::merge(require __DIR__ . '/test-local.php', [
    'components' => [
        'request' => [
            'cookieValidationKey' => 'test',
        ],
    ],
]);
