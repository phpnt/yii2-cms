<?php
/**
 * Created by PhpStorm.
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 19.08.2018
 * Time: 8:43
 */

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => 'Yii2 CMS - Frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'controllerMap' => [
        'bm' => \common\widgets\Basket\controllers\BmController::class,
        'like' => \common\widgets\Like\controllers\LikeController::class,
    ],
    'modules' => [
        'basket' => [
            'class' => 'frontend\modules\basket\Module',
        ],
        'control' => [
            'class' => 'frontend\modules\control\Module',
        ],
        'geo' => [
            'class' => 'frontend\modules\geo\Module',
        ],
        'login' => [
            'class' => 'frontend\modules\login\Module',
        ],
        'signup' => [
            'class' => 'frontend\modules\signup\Module',
        ],
        'profile' => [
            'class' => 'frontend\modules\profile\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    // https://console.developers.google.com/project
                    // http://itwillok.com/auth/index?authclient=google
                    'class' => 'common\widgets\oAuth\oauth\Google',
                    'clientId' => '---',
                    'clientSecret' => '---',
                ],
                'yandex' => [
                    // https://oauth.yandex.ru/client/new
                    // http://itwillok.com/auth/index?authclient=yandex
                    'class' => 'common\widgets\oAuth\oauth\Yandex',
                    'clientId' => '---',
                    'clientSecret' => '---',
                ],
                'facebook' => [
                    // https://developers.facebook.com/apps
                    // http://itwillok.com/auth/index?authclient=facebook
                    'class'         => 'common\widgets\oAuth\oauth\Facebook',
                    'clientId'      => '---',
                    'clientSecret'  => '---',
                ],
                'vkontakte' => [
                    // https://vk.com/editapp?act=create
                    // http://itwillok.com/auth/index?authclient=vkontacte
                    'class'         => 'common\widgets\oAuth\oauth\VKontakte',
                    'clientId'      => '---',
                    'clientSecret'  => '---',
                ],
                'twitter' => [
                    // https://dev.twitter.com/apps/new
                    // http://itwillok.com/auth/index?authclient=twitter
                    'class' => 'common\widgets\oAuth\oauth\Twitter',
                    'consumerKey' => '---',
                    'consumerSecret' => '---',
                ],
                /*'linkedin' => [
                    // https://www.linkedin.com/developer/apps/
                    'class' => 'phpnt\oAuth\oauth\LinkedIn',
                    'clientId' => '---',
                    'clientSecret' => '---',
                ],
                'github' => [
                    // https://github.com/settings/applications/new
                    'class' => 'phpnt\oAuth\oauth\GitHub',
                    'clientId' => '---',
                    'clientSecret' => '---',
                    'scope' => 'user:email, user'
                ],*/
            ]
        ],
        'user' => [
            'identityClass' => \common\models\forms\UserForm::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['login/default/index'],
            'on afterLogin' => function($event) {
                \common\models\forms\UserForm::afterLogin($event->identity->id);
            }
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
            'errorAction' => 'site/error',
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
            'ignoreLanguageUrlPatterns' => [
                '#^geo-manage/#' => '#^geo-manage/#',
            ],
        ],
        'userAccess' => [
            'class' => \common\components\other\UserAccess::class,
        ],
        'emailTemplate' => [
            'class' => \common\components\other\GenerateEmailTemplate::class,
        ],
    ],
    'params' => $params,
];
