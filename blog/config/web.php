<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\PathController',
            'access' => ['@'],
            'disabledCommands' => ['extract', 'archive', 'download', 'mkfile', 'netmount'],
            'root' => [
                'baseUrl'=>'@web',
                'basePath'=>'@webroot',
                'path' => 'imgs/uploaded',
                'name' => 'Images',
                'options' => [
                    //'encoding' => 'WINDOWS-1251',
                    //'uploadOverwrite' => false
                    'uploadMaxSize' => '10M'
                ]
            ],
        ]
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'jklj324hksdf70983hjdfgdfg4444kan',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Admin',
            'enableAutoLogin' => true,
            'loginUrl' => '/login',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '/',
            'rules' => [
                'login' => 'admin/login',
                'admin' => 'admin/index',
                'admin/update/<id:\d+>' => 'admin/update',
                'admin/delete/<id:\d+>' => 'admin/delete',
                'article/<url_name:[a-z-]+>' => 'site/article',
                'page/<page:\d+>' => 'site/index',
                'tag/<request:[a-z-а-я-1-9-A-Z-А-Я]+>/<page:\d+>' => 'site/tags',
                'tag/<request:[a-z-а-я-1-9-A-Z-А-Я]+>' => 'site/tags',
                'search/<request:[a-z-а-я-1-9-A-Z-А-Я]+>/<page:\d+>' => 'site/search',
                'search/<request:[a-z-а-я-1-9-A-Z-А-Я]+>' => 'site/search',
                'subscribe' => 'site/subscribe',
                '' => 'site/index',
                ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
