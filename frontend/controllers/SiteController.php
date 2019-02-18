<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Политика конфиденциальности
     *
     * @return mixed
     */
    public function actionPrivacyPolicy()
    {
        return $this->render('privacy-policy');
    }

    /**
     * Пользовательское соглашение
     *
     * @return mixed
     */
    public function actionUserAgreement()
    {
        return $this->render('user-agreement');
    }


    /**
     * Отображение изображения в модальном окне
     * @return string
     */
    public function actionShowImage($img)
    {
        return $this->renderAjax('show-image', [
            'img' => $img
        ]);
    }
}
