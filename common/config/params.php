<?php
return [
    require_once(__DIR__ . '/../../common/config/functions.php'),
    'frontendUrl' => '',        // домен фронтенда, например http://example.ru, если не указан попробует найти автоматически
    'adminEmail' => 'phpnt@yandex.ru',
    'supportEmail' => 'phpnt@yandex.ru',
    'user.passwordResetTokenExpire' => 3600,
];
