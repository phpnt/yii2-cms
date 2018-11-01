<?php
/**
 * Created by PhpStorm.
 * User: phpNT - http://phpnt.com
 * Date: 09.05.2017
 * Time: 20:18
 */

namespace common\widgets\Elfinder\controllers;

use common\widgets\Elfinder\ConnectorAction;
use Yii;
use yii\web\Controller;

class ElfinderController extends Controller
{
    public function actions()
    {
        return [
            'connector' => [
                'class' => ConnectorAction::class,
                'options' => Yii::$app->request->get('options'),
            ],
        ];
    }
}