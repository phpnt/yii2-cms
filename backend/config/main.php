<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'name' => 'Yii2 CMS - Backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'controllerMap' => [
        'elfinder' => \common\widgets\Elfinder\controllers\ElfinderController::class,
        'csv-manager' => \common\widgets\GetCsv\controllers\CsvManagerController::class,
    ],
    'modules' => [
        'document' => [
            'class' => 'backend\modules\document\Module',
        ],
        'geo' => [
            'class' => 'backend\modules\geo\Module',
        ],
        'i18n' => [
            'class' => 'backend\modules\i18n\Module',
        ],
        'main' => [
            'class' => 'backend\modules\main\Module',
        ],
        'role' => [
            'class' => 'backend\modules\role\Module',
        ],
        'settings' => [
            'class' => 'backend\modules\settings\Module',
        ],
        'user' => [
            'class' => 'backend\modules\user\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => \common\models\forms\UserForm::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['user/login'],
            'on afterLogin' => function($event) {
                \common\models\forms\UserForm::afterLogin($event->identity->id);
            }
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
        'errorHandler' => [
            'errorAction' => 'main/manage/error',
        ],
        'urlManager' => [
            'class' => \common\components\extend\UrlManager::class,
            'languages' => ['ru', 'en'],
            'langLabels' => [
                'ru' => 'Русский',
                'en' => 'English',
            ],
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => [
                [
                    'pattern' => '',
                    'route' => 'main/manage/index',
                ],
                [
                    'pattern' => 'user/<action:(login|logout|request-password-reset|reset-password)>',
                    'route' => 'user/auth/<action>',
                ],
                [
                    'pattern' => '<controller>/<action>/<id:\d+>',
                    'route' => '<controller>/<action>',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<controller>/<action>',
                    'route' => '<controller>/<action>',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<module>/<controller>/<action>/<id:\d+>',
                    'route' => '<module>/<controller>/<action>',
                    'suffix' => ''
                ],
                [
                    'pattern' => '<module>/<controller>/<action>',
                    'route' => '<module>/<controller>/<action>',
                    'suffix' => ''
                ],
            ],
        ]
    ],
    'params' => $params,
];
