<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 01.09.2018
 * Time: 12:57
 */

namespace common\widgets\GetCsv\components;

use yii\base\BaseObject;

class ZipFromFolder extends BaseObject
{
    private $contents;

    public function init()
    {
        parent::init();
    }

    function getZip($source, $destination, $delete = false) {
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new \ZipArchive();
        if (!$zip->open($destination, \ZipArchive::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true){
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file){
                $file = str_replace('\\', '/', $file);

                // Ignore "." and ".." folders
                if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                    continue;

                $file = realpath($file);
                $file = str_replace('\\', '/', $file);

                if (is_dir($file) === true){
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                }else if (is_file($file) === true){
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
                if ($delete) {
                    unlink($file);
                }
            }
        }else if (is_file($source) === true){
            $zip->addFromString(basename($source), file_get_contents($source));
        }
        return $zip->close();
    }
}