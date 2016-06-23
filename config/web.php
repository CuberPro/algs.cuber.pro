<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'algs',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
                '' => 'cubes/index',
                'admin/index' => 'admin/index',
                '<controller:(cases|cubes|subsets)>/create' => '<controller>/create',
                '<controller:(cases|cubes|subsets)>' => '<controller>/index',
                '<cubeId>/<action:(update|delete)>' => 'cubes/<action>',
                '<cubeId>/<subsetName>/<action:(update|delete)>' => 'subsets/<action>',
                '<cubeId>/<subsetName>/<caseName>' => 'cases/view',
                '<cubeId>/<subsetName>' => 'subsets/view',
                '<cubeId>' => 'cubes/view',
            ],
        ],
        'assetManager' => [
            'linkAssets' => true,
            'converter' => [
                'class' => 'yii\web\AssetConverter',
                'commands' => [
                    'less' => ['css', 'lessc {from} {to} --no-color --autoprefix --clean-css'],
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => require(is_file(__DIR__ . '/cookie.local.php') ? __DIR__ . '/cookie.local.php' : __DIR__ . '/cookie.php'),
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-Raw',
                    'fileMap' => [
                        'data' => 'data.php',
                    ],
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(is_file(__DIR__ . '/db.local.php') ? __DIR__ . '/db.local.php' : __DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['::1', '127.0.0.1', gethostbyname('algs.dev.cuber.pro')],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['::1', '127.0.0.1', gethostbyname('algs.dev.cuber.pro')],
    ];
}

return $config;
