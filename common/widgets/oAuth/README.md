phpNT - OAuth 2.0
================================
[![Latest Stable Version](https://poser.pugx.org/phpnt/yii2-oauth/v/stable)](https://packagist.org/packages/phpnt/yii2-oauth) [![Total Downloads](https://poser.pugx.org/phpnt/yii2-oauth/downloads)](https://packagist.org/packages/phpnt/yii2-oauth) [![Latest Unstable Version](https://poser.pugx.org/phpnt/yii2-oauth/v/unstable)](https://packagist.org/packages/phpnt/yii2-oauth) [![License](https://poser.pugx.org/phpnt/yii2-oauth/license)](https://packagist.org/packages/phpnt/yii2-oauth)
### Описание:
#### Авторизация через сторонние сервисы.

### [DEMO](http://phpnt.com/user/login)

------------
[![Donate button](https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif)](http://phpnt.com/donate/index)
------------

### Социальные сети:
 - [Канал YouTube](https://www.youtube.com/c/phpnt)
 - [Группа VK](https://vk.com/phpnt)
 - [Группа facebook](https://www.facebook.com/Phpnt-595851240515413/)

------------

Установка:

------------

```
php composer.phar require "phpnt/yii2-oauth" "*"
```
или

```
composer require phpnt/yii2-oauth "*"
```

или добавить в composer.json файл

```
"phpnt/yii2-oauth": "*"
```
после загрузки, выполнить миграцию
```
yii migrate --migrationPath=@vendor/phpnt/yii2-oauth/migrations
```
## Использование:
### Подключение:
------------
```php
// в файле настройки приложения (main.php - Advanced или web.php - Basic) 
// в controllerMap
...
'controllerMap' => [
        'auth' => [
            'class'         => 'phpnt\oAuth\controllers\AuthController',
            'modelUser'     => 'app\models\User'  // путь к модели User      
        ],
    ],
/**
* В components добавляем компонент authClientCollection
* если в модели app\models\User имеются следующие поля:
*      email       - эл. почта
*      first_name  - имя
*      last_name   - фамилия
*      avatar      - путь к изображению
*      gender      - пол (женский - 1, мужской - 2)
*      status      - статус пользователя (0 - не активированный, 1 - активированный (используется только этот параметр, 2 - заблокированный))
* можно передавать минимальные параметры
*/
'components' => [
    ...
    'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    // https://console.developers.google.com/project
                    'class' => 'phpnt\oAuth\oauth\Google',
                    'clientId' => '---',
                    'clientSecret' => '---',
                ],
                'yandex' => [
                    // https://oauth.yandex.ru/client/new
                    'class' => 'phpnt\oAuth\oauth\Yandex',
                    'clientId' => '---',
                    'clientSecret' => '---',
                ],
                'facebook' => [
                    // https://developers.facebook.com/apps
                    'class'         => 'phpnt\oAuth\oauth\Facebook',
                    'clientId'      => '---',
                    'clientSecret'  => '---',
                ],
                'vkontakte' => [
                    // https://vk.com/editapp?act=create
                    'class'         => 'phpnt\oAuth\oauth\VKontakte',
                    'clientId'      => '---',
                    'clientSecret'  => '---',
                ],
                'twitter' => [
                    // https://dev.twitter.com/apps/new
                    'class' => 'phpnt\oAuth\oauth\Twitter',
                    'consumerKey' => '---',
                    'consumerSecret' => '---',
                ],
                'linkedin' => [
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
                ],
            ]
        ],
],
```
или
```php
// в файле настройки приложения (main.php - Advanced или web.php - Basic) 
// в controllerMap
...
'controllerMap' => [
        'auth' => [
            'class'         => 'phpnt\oAuth\controllers\AuthController',
            'modelUser'     => 'app\models\User'  // путь к модели User      
        ],
    ],
/**
* В components добавляем компонент authClientCollection
* если в модели app\models\User поля не совпадают с полями по умолчанию, указываем их вручную с доп. параметрами:
*/
'components' => [
    ...
    'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    // https://console.developers.google.com/project
                    'class' => 'phpnt\oAuth\oauth\Google',
                    'email'         => 'email_field_in_User',
                    'first_name'    => 'first_name_field_in_User',
                    'last_name'     => 'last_name_field_in_User',
                    /* Поле для изображения пользователя */
                    'avatar'        => 'avatar_field_in_User',
                    /* Поле пол и значение М/Ж */
                    'gender'        => 'gender_field_in_User',
                    'female'        => 2,       // значение для женского пола
                    'male'          => 1,       // значение для мужского пола
                    /* Поле статус и значение активного пользователя */
                    'status'        => 'status_field_in_User',
                    'statusActive'  => 1,       // значение для активного пользователя
                    'clientId' => '---',
                    'clientSecret' => '---',
                ],
                ...
            ]
        ],
],
```

### В представлении, где нужна авторизация OAuth 2.0 добавляем:
------------
```php
use phpnt\oAuth\AuthChoice;

// виджет, выводит список сервисов, с помощью которых возможно авторизоваться
echo AuthChoice::widget(['baseAuthUrl' => ['/auth/index']]);
```
# Документация (примеры):
## [AuthClient Extension for Yii 2](http://www.yiiframework.com/doc-2.0/ext-authclient-index.html)
## [OAuth 2.0](http://oauth.net/2/)
------------
### Версия:
### 0.0.1
------------
### Лицензия:
### [MIT](https://ru.wikipedia.org/wiki/%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F_MIT)
------------
