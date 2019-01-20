<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 22.09.2018
 * Time: 19:03
 */

namespace common\components\extend;

defined('YII2_LOCALEURLS_TEST') || define('YII2_LOCALEURLS_TEST', false);

use Yii;
use common\models\Constants;
use yii\base\InvalidConfigException;
use yii\web\Cookie;
use yii\web\UrlManager as BaseUrlManager;
use yii\web\NotFoundHttpException;
use yii\base\Exception;
use yii\helpers\Url;

class UrlManager extends BaseUrlManager
{
    /*
     * Город по умолчанию
     * */
    public $defaultCity;

    /**
     * @var array list of available language codes. More specific patterns should come first, e.g. 'en_us'
     * before 'en'. This can also contain mapping of <url_value> => <language>, e.g. 'english' => 'en'.
     */
    public $languages = [];

    public $langLabels = [];

    /**
     * включает специальные ссылки для локали и гео
     * @var bool whether to enable locale URL specific features
     */
    public $enableLocaleUrls = true;

    /**
     * @var bool Должен ли язык по умолчанию использовать код URL, как любой другой настроенный язык.
     *
     * By default this is `false`, so for URLs without a language code the default language is assumed.
     * In addition any request to an URL that contains the default language code will be redirected to
     * the same URL without a language code. So if the default language is `fr` and a user requests
     * `/fr/some/page` he gets redirected to `/some/page`. This way the persistet language can be reset
     * to the default language.
     *
     * If this is `true`, then an URL that does not contain any language code will be redirected to the
     * same URL with default language code. So if for example the default language is `fr`, then
     * any request to `/some/page` will be redirected to `/fr/some/page`.
     *
     */
    public $enableDefaultLanguageUrlCode = false;

    /**
     * @var bool Определять ли язык приложения из заголовков HTTP (то есть настроек браузера).
     * Default is `true`.
     */
    public $enableLanguageDetection = true;

    /**
     * @var bool Сохранять ли обнаруженный язык в сессии и (необязательно) куки.
     * Если да `true` (по умолчанию) и возвращающийся пользователь пытается получить доступ к любому URL без языкового префикса,
     * он будет перенаправлен на соответствующий сохраненный язык URL (e.g. /some/page -> /fr/some/page).
     */
    public $enableLanguagePersistence = true;

    /**
     * @var bool Сохранять ли коды языков верхнего регистра в URL. По умолчанию установлено значение `false`, например,
     * redirect `de-AT` to `de-at`.
     */
    public $keepUppercaseLanguageCode = false;

    /**
     * @var string the name of the session key that is used to store the language. Default is '_language'.
     */
    public $languageSessionKey = '_language';

    /**
     * @var string the name of the language cookie. Default is '_language'.
     */
    public $languageCookieName = '_language';

    /**
     * @var int number of seconds how long the language information should be stored in cookie,
     * if `$enableLanguagePersistence` is true. Set to `false` to disable the language cookie completely.
     * Default is 30 days.
     */
    public $languageCookieDuration = 2592000;

    /**
     * @var array configuration options for the language cookie. Note that `$languageCookieName`
     * and `$languageCookeDuration` will override any `name` and `expire` settings provided here.
     */
    public $languageCookieOptions = [];

    /**
     * @var array list of route and URL regex patterns to ignore during language processing. The keys
     * of the array are patterns for routes, the values are patterns for URLs. Route patterns are checked
     * during URL creation. If a pattern matches, no language parameter will be added to the created URL.
     * URL patterns are checked during processing incoming requests. If a pattern matches, the language
     * processing will be skipped for that URL. Examples:
     *
     * ~~~php
     * [
     *     '#^site/(login|register)#' => '#^(login|register)#'
     *     '#^api/#' => '#^api/#',
     * ]
     * ~~~
     */
    public $ignoreLanguageUrlPatterns = [];

    /**
     * @var string the language that was initially set in the application configuration
     */
    protected $_defaultLanguage;

    /**
     * @var string город по умолчанию
     */
    protected $_defaultCity;

    /**
     * @inheritdoc
     */
    public $enablePrettyUrl = true;

    /**
     * @var string if a parameter with this name is passed to any `createUrl()` method, the created URL
     * will use the language specified there. URLs created this way can be used to switch to a different
     * language. If no such parameter is used, the currently detected application language is used.
     */
    public $languageParam = 'language';

    public $cityParam = 'id_geo_city';

    /**
     * @var \yii\web\Request
     */
    protected $_request;

    /**
     * @var bool whether locale URL was processed
     */
    protected $_processed = false;

    /**
     * Создаем ЧПУ для разделов
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $site = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['alias' => 'site'])
            ->one();

        /* В зависимости от доcтупа извлекаем навигацию (http://screenshot.ru/88dd5a039c55d5a8f8c3808fcb1e02d9) */
        if (Yii::$app->user->isGuest) {
            $navigation = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'status' => Constants::STATUS_DOC_ACTIVE,
                    'parent_id' => $site['id'],
                    'access' => [Constants::ACCESS_ALL, Constants::ACCESS_GUEST],
                ])
                ->orderBy(['position' => SORT_ASC])
                ->all();
        } else {
            $navigation = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'status' => Constants::STATUS_DOC_ACTIVE,
                    'parent_id' => $site['id'],
                    'access' => [Constants::ACCESS_ALL, Constants::ACCESS_USER],
                ])
                ->orderBy(['position' => SORT_ASC])
                ->all();
        }

        foreach ($navigation as $item) {
            $rules[] = [
                'pattern' => 'auth/<action>',
                'route' => 'auth/<action>',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => 'site/<action>',
                'route' => 'site/<action>',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => 'geo-manage/<action>',
                'route' => 'geo-manage/<action>',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => 'profile/<controller>/<action>',
                'route' => 'profile/default/<action>',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => 'login/<controller>/<action>',
                'route' => 'login/default/<action>',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => 'signup/<controller>/<action>',
                'route' => 'signup/default/<action>',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => 'geo/<controller>/<action>',
                'route' => 'geo/default/<action>',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => 'basket/<controller>/<action>',
                'route' => 'basket/default/<action>',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => 'bm/update',
                'route' => 'bm/update',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => 'bm/refresh',
                'route' => 'bm/refresh',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => 'rating/<action>',
                'route' => 'rating/<action>',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => 'comment/<action>',
                'route' => 'comment/<action>',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => '<alias>',
                'route' => 'control/default/index',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => '<alias>/<parent>/<item_alias>',
                'route' => 'control/default/view',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => '<alias>/<folder_alias>',
                'route' => 'control/default/view-list',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => '<alias>',
                'route' => 'control/default/index',
                'suffix' => ''
            ];
            $rules[] = [
                'pattern' => '',
                'route' => 'control/default/index',
                'suffix' => ''
            ];
        }

        $this->addRules($rules);

        if ($this->enableLocaleUrls && $this->languages) {
            if (!$this->enablePrettyUrl) {
                throw new InvalidConfigException('Locale URL support requires enablePrettyUrl to be set to true.');
            }
        }
        $this->_defaultLanguage = Yii::$app->language;
        $this->_defaultCity = $this->defaultCity;
    }

    /**
     * @return string the `language` option that was initially set in the application config file,
     * before it was modified by this component.
     */
    public function getDefaultLanguage()
    {
        return $this->_defaultLanguage;
    }

    /**
     * @return string возвращает город по умолчанию, до изменения данным компонентом.
     */
    public function getDefaultCity()
    {
        return $this->_defaultCity;
    }

    /**
     * Выполняется первая
     * @inheritdoc
     */
    public function parseRequest($request)
    {
        if ($this->enableLocaleUrls && $this->languages) {
            $process = true;
            if ($this->ignoreLanguageUrlPatterns) {
                // маршруты, которые игнорируются
                $pathInfo = $request->getPathInfo();
                foreach ($this->ignoreLanguageUrlPatterns as $k => $pattern) {
                    if (preg_match($pattern, $pathInfo)) {
                        Yii::trace("Ignore pattern '$pattern' matches '$pathInfo.' Skipping language processing.", __METHOD__);
                        $process = false;
                    }
                }
            }
            if ($process && !$this->_processed) {
                // обработка ссылки
                $this->_processed = true;
                $this->processLocaleUrl($request);
            }
        }

        return parent::parseRequest($request);
    }

    /**
     * Выполняется вторая
     * Проверяет параметр языка или локали в URL и перезаписывает pathInfo, если найден.
     * Если параметр не найден, он попытается определить язык из постоянного хранилища (сессия /
     * куки) или из настроек браузера.
     *
     * @var \yii\web\Request $request
     */
    protected function processLocaleUrl($request)
    {
        $this->_request = $request;
        $pathInfo = $request->getPathInfo();
        $parts = [];
        foreach ($this->languages as $k => $v) {
            $value = is_string($k) ? $k : $v;
            if (substr($value, -2)==='-*') {
                $lng = substr($value, 0, -2);
                $parts[] = "$lng\-[a-z]{2,3}";
                $parts[] = $lng;
            } else {
                $parts[] = $value;
            }
        }
        $pattern = implode('|', $parts);

        if (preg_match("#^($pattern)\b(/?)#i", $pathInfo, $m)) {
            // если язык изменился
            $substr = mb_substr($pathInfo, mb_strlen($m[1].$m[2]));

            /*
             * Если выбран город, т.е. путь равен <id_geo_city>/<путь> ... / .., удаляем id_geo_city/
             * Пишем гео данные в сессии и куки
             * */
            $pathArray = explode('/', $substr);
            $id_geo_city = $this->defaultCity;
            if (is_numeric($pathArray[0])) {
                $substr = $this->setGeo($pathArray[0], $substr);
                $id_geo_city = $pathArray[0];
            } else {
                $substr = $this->setGeo($id_geo_city, $substr);
            }

            // Устанавливает информацию о пути текущего запроса.
            $request->setPathInfo($substr);
            $code = $m[1];

            if (isset($this->languages[$code])) {
                // Replace alias with language code
                $language = $this->languages[$code];
            } else {
                // lowercase language, uppercase country
                list($language,$country) = $this->matchCode($code);
                if ($country !== null) {
                    if ($code === "$language-$country" && !$this->keepUppercaseLanguageCode) {
                        // Сохранять ли коды языков верхнего регистра в URL.
                        $this->redirectToLanguage(strtolower($code));   // Redirect ll-CC to ll-cc
                    } else {
                        $language = "$language-$country";
                    }
                }
                if ($language === null) {
                    $language = $code;
                }
            }

            Yii::$app->language = $language;
            Yii::trace("Language code found in URL. Setting application language to '$language'.", __METHOD__);
            if ($this->enableLanguagePersistence) {
                Yii::$app->session[$this->languageSessionKey] = $language;
                Yii::trace("Persisting language '$language' in session.", __METHOD__);
                if ($this->languageCookieDuration) {
                    $cookie = new Cookie(array_merge(
                        ['httpOnly' => true],
                        $this->languageCookieOptions,
                        [
                            'name' => $this->languageCookieName,
                            'value' => $language,
                            'expire' => time() + (int) $this->languageCookieDuration,
                        ]
                    ));
                    Yii::$app->getResponse()->getCookies()->add($cookie);
                    Yii::trace("Persisting language '$language' in cookie.", __METHOD__);
                }
            }

            // "Reset" case: We called e.g. /fr/demo/page so the persisted language was set back to "fr".
            // Now we can redirect to the URL without language prefix, if default prefixes are disabled.

            if (!$this->enableDefaultLanguageUrlCode && $language === $this->_defaultLanguage && $id_geo_city === $this->defaultCity) {
                // если язык по умолчанию и город по умолчанию
                $this->redirectToLanguage('', $id_geo_city);
            }
        } else {
            /*
             * Если выбран город, т.е. путь равен <id_geo_city>/<путь> ... / .., удаляем id_geo_city/
             * Пишем гео данные в сессии и куки
             * если язык не изменился
             * */
            $substr = $pathInfo;
            $pathArray = explode('/', $substr);
            $id_geo_city = $this->defaultCity;
            if (is_numeric($pathArray[0])) {
                $substr = $this->setGeo($pathArray[0], $substr);
                $id_geo_city = $pathArray[0];
                $request->setPathInfo($substr);
            } else {
                $substr = $this->setGeo($id_geo_city, $substr);
                $request->setPathInfo($substr);
            };

            $language = $this->_defaultLanguage;

            if ($language === null && $this->enableLanguageDetection) {
                // $this->enableLanguageDetection - Определять язык приложения из заголовков HTTP (то есть настроек браузера).
                foreach ($request->getAcceptableLanguages() as $acceptable) {
                    list($language,$country) = $this->matchCode($acceptable);
                    if ($language !== null) {
                        $language = $country === null ? $language : "$language-$country";
                        Yii::trace("Обнаружен язык браузера '$language'.", __METHOD__);
                        break;
                    }
                }
            }
            if ($language === null || $language === $this->_defaultLanguage) {
                if (!$this->enableDefaultLanguageUrlCode) {
                    // $this->enableDefaultLanguageUrlCode -> Должен язык по умолчанию использовать код URL, как любой другой настроенный язык.
                    // Скрываем язык по умолчанию.
                    if ($id_geo_city === $this->defaultCity) {
                        return '';
                    } else {
                        return $id_geo_city;
                    }
                } else {
                    // $this->enableDefaultLanguageUrlCode -> Должен язык по умолчанию использовать код URL, как любой другой настроенный язык.
                    // Отображаем язык по умолчанию.
                    $language = $this->_defaultLanguage;
                }
            }

            // #35: Перенаправлять только в том случае, если был найден правильный язык
            if ($this->matchCode($language) === [null, null]) {
                return '';
            }

            $key = array_search($language, $this->languages);
            if ($key && is_string($key)) {
                $language = $key;
            }

            // Если сохранять коды языков верхнего регистра в URL, отправляем язык как если, иначе переводим в нижний регистр
            $language = $this->keepUppercaseLanguageCode ? $language : strtolower($language);
            $this->redirectToLanguage($language, $id_geo_city);
        }
    }

    /**
     * Выполняется третья, если используется язык отличный от языка по умолчанию
     *
     * Проверяет, соответствует ли данный код любому из настроенных языков.
     *
     * Если код является кодом одного языка и соответствует
     *
     *  - точный язык в соответствии с настройками (ll)
     *  - язык со знаком страны (ll- *)
     *
     * этот код языка возвращается.
     *
     * Если код также содержит код страны и соответствует
     *
     *  - точный язык / код страны в соответствии с настройкой (ll-CC)
     *  - язык со знаком страны (ll- *)
     *
     * код с заглавной страной возвращается. Если только языковая часть совпадает
     * с настроенным языком, этот язык возвращается.
     *
     * @param string $code код для соответствия
     * @return array of [language, country], [language, null] or [null, null] if no match
     */
    protected function matchCode($code)
    {
        $language = $code;
        $country = null;
        $parts = explode('-', $code);
        if (count($parts)===2) {
            $language = $parts[0];
            $country = strtoupper($parts[1]);
        }

        if (in_array($code, $this->languages)) {
            return [$language, $country];
        } elseif (
            $country && in_array("$language-$country", $this->languages) ||
            in_array("$language-*", $this->languages)
        ) {
            return [$language, $country];
        } elseif (in_array($language, $this->languages)) {
            return [$language, null];
        } else {
            return [null, null];
        }
    }

    /**
     * Выполняется четвертая
     * Перенаправить на текущий URL с указанным языковым кодом
     *
     * @param string $language код языка для добавления. Также может быть пустым, чтобы не добавлять код языка.
     * @throws NotFoundHttpException
     * @throws Exception
     */
    protected function redirectToLanguage($language, $id_geo_city)
    {
        $result = parent::parseRequest($this->_request);

        if ($result === false) {
            throw new NotFoundHttpException(Yii::t('yii', 'Страница не найдена.'));
        }

        list($route, $params) = $result;

        if($language){
            $params[$this->languageParam] = $language;
        }

        // See Yii Issues #8291 and #9161:
        $params = $params + $this->_request->getQueryParams();
        array_unshift($params, $route);
        $url = $this->createUrl($params);
        // Required to prevent double slashes on generated URLs
        if ($this->suffix === '/' && $route === '') {
            $url = rtrim($url, '/').'/';
        }
        Yii::trace("Redirecting to $url.", __METHOD__);
        Yii::$app->getResponse()->redirect($url);
        if (YII2_LOCALEURLS_TEST) {
            // Response::redirect($url) above will call `Url::to()` internally. So to really
            // test for the same final redirect URL here, we need to call Url::to(), too.
            throw new Exception(Url::to($url));
        } else {
            Yii::$app->end();
        }
    }

    /**
     * Выполняется пятая
     * Как URL должен выглядеть
     * Возвращает сформированный Url
     *
     * @inheritdoc
     */
    public function createUrl($params)
    {
        if ($this->ignoreLanguageUrlPatterns) {
            // маршруты при которых url не меняется
            $params = (array) $params;
            $route = trim($params[0], '/');
            foreach ($this->ignoreLanguageUrlPatterns as $pattern => $v) {
                if (preg_match($pattern, $route)) {
                    $url = parent::createUrl($params);
                    return $url;
                }
            }
        }

        if ($this->enableLocaleUrls && $this->languages) {
            // если специальные маршруты включены и есть выбор языков
            $params = (array) $params;

            if (isset($params[$this->languageParam])) {
                $language = $params[$this->languageParam];
                unset($params[$this->languageParam]);
                $languageRequired = true;
            } else {
                $language = Yii::$app->language;
                $languageRequired = false;
            }

            if (isset($params[$this->cityParam])) {
                $id_geo_city = $params[$this->cityParam];
                unset($params[$this->cityParam]);
            } else {
                $session = Yii::$app->session;
                // ищем id_geo_city сесиях и куки
                $id_geo_city = $session->get('id_geo_city');
                if ($id_geo_city === null) {
                    $cookiesRequest = Yii::$app->request->cookies;
                    if (isset($cookiesRequest['id_geo_city'])) {
                        $id_geo_city = $cookiesRequest['id_geo_city']->value;
                    }
                }
                if (!$id_geo_city) {
                    $id_geo_city = $this->defaultCity;
                }
            }

            // Не используйте префикс для языка по умолчанию, чтобы предотвратить ненужное перенаправление, если нет постоянства и нет обнаружения
            if (
                $languageRequired && $language === $this->getDefaultLanguage() &&
                !$this->enableDefaultLanguageUrlCode && !$this->enableLanguagePersistence && !$this->enableLanguageDetection
            ) {
                $languageRequired = false;
            }

            $url = parent::createUrl($params);

            // Если в параметрах явно не указан язык, мы можем вернуть URL без префикса
            // для языка по умолчанию, если суффиксы отключены для языка по умолчанию. В любом другом случае мы
            // всегда добавляйте суффикс, например создать «reset» URL, которые явно содержат язык по умолчанию.
            if (!$languageRequired && !$this->enableDefaultLanguageUrlCode && $language === $this->getDefaultLanguage() && $id_geo_city === $this->getDefaultCity()) {
                // если язык и город по умолчанию
                return $url;
            } elseif ($language === $this->getDefaultLanguage() && $id_geo_city != $this->getDefaultCity()) {
                // если язык по умолчанию, а город не по умолчанию
                return '/'.$id_geo_city.$url;
            } else {
                // если язык не по умолчанию и город не по умолчанию или
                // если язык не по умолчанию, а город по умолчанию
                $key = array_search($language, $this->languages);
                if (is_string($key)) {
                    $language = $key;
                }
                // Язык в нижний регистр
                if (!$this->keepUppercaseLanguageCode) {
                    $language = strtolower($language);
                }

                if ($id_geo_city != $this->defaultCity) {
                    $language = $language . '/' . $id_geo_city;
                }

                // Удалить все косые черты, если они не настроены как суффикс
                if ($this->suffix !== '/') {
                    if (count($params) !== 1) {
                        $url = preg_replace('#/\?#', '?', $url);
                    } else {
                        $url = rtrim($url, '/');
                    }
                }

                // /foo/bar -> /de/foo/bar
                // /base/url/foo/bar -> /base/url/de/foo/bar
                // /base/index.php/foo/bar -> /base/index.php/de/foo/bar
                // http://www.example.com/base/url/foo/bar -> http://www.example.com/base/de/foo/bar
                $needle = $this->showScriptName ? $this->getScriptUrl() : $this->getBaseUrl();
                // Check for server name URL
                if (strpos($url, '://')!==false) {
                    if (($pos = strpos($url, '/', 8))!==false || ($pos = strpos($url, '?', 8))!==false) {
                        $needle = substr($url, 0, $pos) . $needle;
                    } else {
                        $needle = $url . $needle;
                    }
                }
                $needleLength = strlen($needle);
                $needleLength = $needleLength ? substr_replace($url, "$needle/$language", 0, $needleLength) : "/$language$url";
                return $needleLength;
            }
        } else {
            $url = parent::createUrl($params);
            return $url;
        }
    }

    /**
     * Функция установки города.
     * Запись в сессии и куки
     * @param string $id_geo_city Передает ID города
     * @param string $substr Текущий путь
     * @return string Текущий маршрут
     * */
    protected function setGeo($id_geo_city, $substr) {
        $session = Yii::$app->session;
        $id_geo_city_storage = $session->get('id_geo_city');

        if (!$id_geo_city_storage) {
            $cookiesRequest = Yii::$app->request->cookies;
            if (isset($cookiesRequest['id_geo_city'])) {
                $id_geo_city_storage = $cookiesRequest['id_geo_city']->value;
            }
        }

        if ($id_geo_city_storage != $id_geo_city) {
            $city = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_city')
                ->where(['id_geo_city' => $id_geo_city])
                ->one();

            if ($city) {
                Yii::$app->session['id_geo_city'] = $city['id_geo_city'];
                $cookie = new Cookie(array_merge(
                    ['httpOnly' => true],
                    $this->languageCookieOptions,
                    [
                        'name' => 'id_geo_city',
                        'value' => $city['id_geo_city'],
                        'expire' => time() + (int)$this->languageCookieDuration,
                    ]
                ));
                Yii::$app->getResponse()->getCookies()->add($cookie);

                $region = (new \yii\db\Query())
                    ->select(['*'])
                    ->from('geo_region')
                    ->where(['id_geo_region' => $city['id_geo_region']])
                    ->one();

                if ($region) {
                    Yii::$app->session['id_geo_region'] = $region['id_geo_region'];
                    $cookie = new Cookie(array_merge(
                        ['httpOnly' => true],
                        $this->languageCookieOptions,
                        [
                            'name' => 'id_geo_region',
                            'value' => $region['id_geo_region'],
                            'expire' => time() + (int)$this->languageCookieDuration,
                        ]
                    ));
                    Yii::$app->getResponse()->getCookies()->add($cookie);

                    $country = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('geo_country')
                        ->where(['id_geo_country' => $region['id_geo_country']])
                        ->one();

                    if ($country) {
                        Yii::$app->session['id_geo_country'] = $country['id_geo_country'];
                        $cookie = new Cookie(array_merge(
                            ['httpOnly' => true],
                            $this->languageCookieOptions,
                            [
                                'name' => 'id_geo_country',
                                'value' => $country['id_geo_country'],
                                'expire' => time() + (int)$this->languageCookieDuration,
                            ]
                        ));
                        Yii::$app->getResponse()->getCookies()->add($cookie);
                    }
                }
            }
        }

        // Устанавливаем путь, удаляя ID города
        $substr = str_replace($id_geo_city . '/', '', $substr);
        $substr = str_replace($id_geo_city, '', $substr);

        return $substr;
    }
}