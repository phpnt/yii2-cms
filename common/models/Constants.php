<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:47
 */

namespace common\models;

class Constants
{
    // Статусы пользователя
    const STATUS_BLOCKED = 0;   // заблокирован
    const STATUS_ACTIVE = 1;    // активен
    const STATUS_WAIT = 2;      // ожидает подтверждения

    // Доступы пользователя
    const ACCESS_ALL = 1;       // всем пользователям
    const ACCESS_GUEST = 2;     // гостям
    const ACCESS_USER = 3;      // авторизованным пользователям

    // Статусы документов
    const STATUS_DOC_BLOCKED = 0;   // заблокирован
    const STATUS_DOC_ACTIVE = 1;    // активен
    const STATUS_DOC_WAIT = 2;      // ожидает подтверждения

    // Пол пользователя
    const SEX_FEMALE    = 1;
    const SEX_MALE      = 2;

    // Пол пользователя
    const BASKET_BUTTON_FOR_ONE     = 1;
    const BASKET_BUTTON_FOR_MANY    = 2;

    // Время действия токенов
    const EXPIRE = 3600;

    // Расширение сохраняемого файла изобрежния
    // Определение Mime не предусмотрено. Файлы
    // изобрежния в соц. сетях часто без расширения в
    // названиях
    const EXT = '.jpg';

    // Параметры RBAC
    const TYPE_ROLE    = 1;
    const TYPE_PERMISSION = 2;

    // типы поле
    const FIELD_TYPE_INT        = 1; // Целое число +
    const FIELD_TYPE_INT_RANGE  = 2; // Диапазон целых чисел
    const FIELD_TYPE_FLOAT      = 3; // Число с дробью
    const FIELD_TYPE_FLOAT_RANGE = 4; // Диапазон чисел с дробью
    const FIELD_TYPE_STRING     = 5; // Строка
    const FIELD_TYPE_TEXT       = 6; // Текст
    const FIELD_TYPE_CHECKBOX   = 7; // Чекбокс
    const FIELD_TYPE_RADIO      = 8; // Радиокнопка
    const FIELD_TYPE_LIST       = 9; // Список
    const FIELD_TYPE_LIST_MULTY = 10; // Список с мультивыбором
    const FIELD_TYPE_PRICE      = 11; // Цена
    const FIELD_TYPE_DATE       = 12; // Дата
    const FIELD_TYPE_DATE_RANGE = 13; // Диапазон дат
    const FIELD_TYPE_ADDRESS    = 14; // Адрес
    const FIELD_TYPE_CITY       = 15; // Город
    const FIELD_TYPE_REGION     = 16; // Регион
    const FIELD_TYPE_COUNTRY    = 17; // Страна
    const FIELD_TYPE_EMAIL      = 18; // Эл. почта
    const FIELD_TYPE_URL        = 19; // Ссылка
    const FIELD_TYPE_SOCIAL     = 20; // Страница соц. сети
    const FIELD_TYPE_YOUTUBE    = 21; // Видео YouTube
    const FIELD_TYPE_FILE       = 22; // Файл
    const FIELD_TYPE_FEW_FILES  = 23; // Несколько файлов

    // расширения для файлов
    const FILE_EXT_JPEG         = 'jpeg'; //
    const FILE_EXT_JPG          = 'jpg'; //
    const FILE_EXT_PNG          = 'png'; //
    const FILE_EXT_PSD          = 'psd'; //
    const FILE_EXT_PDF          = 'pdf'; //
    const FILE_EXT_DOC          = 'doc'; //
    const FILE_EXT_DOCX         = 'docx'; //
    const FILE_EXT_XLS          = 'xls'; //
    const FILE_EXT_XLSX         = 'xlsx'; //
    const FILE_EXT_TXT          = 'txt'; //
    const FILE_EXT_MP3          = 'mp3'; //
    const FILE_EXT_WAV          = 'wav'; //
    const FILE_EXT_AVI          = 'avi'; //
    const FILE_EXT_MPG          = 'mpg'; //
    const FILE_EXT_MPEG         = 'mpeg'; //
    const FILE_EXT_MPEG_4       = 'mpeg_4'; //
    const FILE_EXT_DIVX         = 'divx'; //
    const FILE_EXT_DJVU         = 'djvu'; //
    const FILE_EXT_FB2          = 'fb2'; //
    const FILE_EXT_RAR          = 'rar'; //
    const FILE_EXT_ZIP          = 'zip'; //
}