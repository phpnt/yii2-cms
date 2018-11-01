<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 25.07.2018
 * Time: 9:49
 */

namespace common\widgets\GetCsv\components;

use yii\base\BaseObject;

/* $csvManager->generateCsv($result, $delimiter = ';', $enclosure = '"') */
class CsvComponent extends BaseObject
{
    private $contents;

    public function init()
    {
        parent::init();
    }

    function generateCsv($data, $delimiter = ',', $enclosure = '"') {
        $content = '';
        $handle = fopen('php://temp', 'r+');
        foreach ($data as $line) {
            fputcsv($handle, $line, $delimiter, $enclosure);
        }
        rewind($handle);
        while (!feof($handle)) {
            $content .= fread($handle, 8192);
        }
        fclose($handle);
        return $content;
    }
}