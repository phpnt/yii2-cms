<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 23.09.2018
 * Time: 8:08
 */

namespace common\components\other;

use yii\base\Object;

class GenerateEmailTemplate extends Object
{
    public function init()
    {
        parent::init();
    }

    public function getTemplate($template, $data, $counter = 5)
    {
        $i = 0;
        while ($i < $counter) {
            if (isset($data['{NAME_'.$i.'}'])) {
                $template = str_replace(['{NAME_'.$i.'}'], $data['{NAME_'.$i.'}'], $template);
            }
            $i++;
        }

        $i = 0;
        while ($i < $counter) {
            if (isset($data['{EMAIL_'.$i.'}'])) {
                $template = str_replace(['{EMAIL_'.$i.'}'], $data['{EMAIL_'.$i.'}'], $template);
            }
            $i++;
        }

        $i = 0;
        while ($i < $counter) {
            if (isset($data['{URL_'.$i.'}'])) {
                $template = str_replace(['{URL_'.$i.'}'], $data['{URL_'.$i.'}'], $template);
            }
            $i++;
        }

        $i = 0;
        while ($i < $counter) {
            if (isset($data['{DATE_'.$i.'}'])) {
                $template = str_replace(['{DATE_'.$i.'}'], $data['{DATE_'.$i.'}'], $template);
            }
            $i++;
        }

        $i = 0;
        while ($i < $counter) {
            if (isset($data['{TEXT_'.$i.'}'])) {
                $template = str_replace(['{TEXT_'.$i.'}'], $data['{TEXT_'.$i.'}'], $template);
            }
            $i++;
        }

        return $template;
    }
}