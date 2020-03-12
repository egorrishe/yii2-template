<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'modules' => [
        'blog' => [
            'class' => 'app\modules\blog\Module',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
    ],
];
