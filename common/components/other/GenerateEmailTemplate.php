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
        $template = $this->generateTemplate('NAME', $template, $data, $counter);
        $template = $this->generateTemplate('EMAIL', $template, $data, $counter);
        $template = $this->generateTemplate('PASS', $template, $data, $counter);
        $template = $this->generateTemplate('URL', $template, $data, $counter);
        $template = $this->generateTemplate('DATE', $template, $data, $counter);
        $template = $this->generateTemplate('TEXT', $template, $data, $counter);

        return $template;
    }

    private function generateTemplate($name, $template, $data, $counter) {
        $i = 0;
        while ($i < $counter) {
            if (isset($data['{' . $name . '_'.$i.'}'])) {
                $template = str_replace(['{' . $name . '_'.$i.'}'], $data['{' . $name . '_'.$i.'}'], $template);
            }
            $i++;
        }

        return $template;
    }
}