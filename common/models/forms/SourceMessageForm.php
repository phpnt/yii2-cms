<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 28.10.2018
 * Time: 19:58
 */

namespace common\models\forms;

use common\models\extend\SourceMessageExtend;

class SourceMessageForm extends SourceMessageExtend
{
    public function saveMessages($messages)
    {
        foreach ($messages as $key => $value) {
            MessageForm::deleteAll([
                'id' => $this->id,
                'language' => $key
            ]);

            $msgHash = md5(time());

            if ($value) {
                $modelMessage = new MessageForm();
                $modelMessage->id = $this->id;
                $modelMessage->language = $key;
                $modelMessage->translation = $value;
                $modelMessage->hash = $msgHash;
                $modelMessage = $modelMessage->save();

                if (!$modelMessage) {
                    return false;
                }
            }
        }
        return true;
    }
}