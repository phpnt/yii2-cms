<?php

namespace common\widgets\Elfinder;

use Yii;
use yii\base\Action;
use yii\web\Response;

/**
 * Class ConnectorAction
 * @package alexantr\elfinder
 */
class ConnectorAction extends Action
{
    /**
     * @var array
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        (new \elFinderConnector(new \elFinder($this->options)))->run();
    }
}
