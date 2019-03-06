<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 29.10.2018
 * Time: 14:04
 */

namespace common\widgets\Basket\controllers;

use common\models\Constants;
use common\models\forms\BasketForm;
use common\models\forms\DocumentForm;
use Yii;
use yii\base\ErrorException;
use yii\db\StaleObjectException;
use yii\web\Controller;

class BmController extends Controller
{
    /**
     * Управление кнопкой "В корзину"
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionAddItem($document_id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->goHome();
        }

        $parentData = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['alias' => 'basket'])
            ->one();

        $modelDocumentForm = new DocumentForm();
        $modelDocumentForm->scenario = 'create-element';
        $time = time();
        $modelDocumentForm->name = 'basket-' . $time;
        $modelDocumentForm->alias = 'basket-' . $time;
        $modelDocumentForm->status = Constants::STATUS_DOC_ACTIVE;
        $modelDocumentForm->template_id = $parentData['template_id'];
        $modelDocumentForm->item_id = $document_id;
        $modelDocumentForm->load(Yii::$app->request->post());

        if ($modelDocumentForm->validate()) {
            if (Yii::$app->user->isGuest) {
                $item = (new \yii\db\Query())
                    ->select(['*'])
                    ->addSelect(['value_numeric.value AS basket_items'])
                    ->from('document')
                    ->innerJoin('value_numeric', 'value_numeric.document_id = document.id')
                    ->where([
                        'document.parent_id' => $parentData['id'],
                        'document.item_id' => $document_id,
                    ])
                    ->andWhere([
                        'ip' => Yii::$app->request->userIP,
                        'user_agent' => Yii::$app->request->userAgent,
                    ])
                    ->one();
            } else {
                $item = (new \yii\db\Query())
                    ->select(['*'])
                    ->addSelect(['value_numeric.value AS basket_items'])
                    ->from('document')
                    ->innerJoin('value_numeric', 'value_numeric.document_id = document.id')
                    ->where([
                        'document.parent_id' => $parentData['id'],
                        'document.item_id' => $document_id,                    ])
                    ->andWhere([
                        'created_by' => Yii::$app->user->id
                    ])
                    ->one();
            }

            $valuePrice = (new \yii\db\Query())
                ->select(['*'])
                ->from('value_price')
                ->where([
                    'document_id' => $document_id,
                ])
                ->one();

            if (!$valuePrice['item_max']) {
                $valuePrice['item_max'] = $valuePrice['item_store'];
            }

            if (!$item) {
                // если нет записи, создаем ее
                if ($modelDocumentForm->items_number <= $valuePrice['item_max']) {
                    Yii::$app->session->set(
                        'message',
                        [
                            'type' => 'success',
                            'icon' => 'glyphicon glyphicon-ok',
                            'message' => Yii::t('app', 'Успешно'),
                        ]
                    );
                    if (!$modelDocumentForm->save()) {
                        dd($modelDocumentForm->errors);
                    }
                }
            } else {
                // если есть запись
                $item['basket_items'] = $item['basket_items'] + $modelDocumentForm->items_number;
                if ($item['basket_items'] <= $valuePrice['item_max']) {
                    Yii::$app->session->set(
                        'message',
                        [
                            'type' => 'success',
                            'icon' => 'glyphicon glyphicon-ok',
                            'message' => Yii::t('app', 'Успешно'),
                        ]
                    );
                    Yii::$app->db->createCommand()
                        ->update('value_numeric', ['value' => $item['basket_items']], ['id' => $item['id']])
                        ->execute();
                } else {
                    Yii::$app->session->set(
                        'message',
                        [
                            'type' => 'danger',
                            'icon' => 'glyphicon glyphicon-ban',
                            'message' => Yii::t('app', 'У Вас уже есть максимальное количество данного товара в корзине.'),
                        ]
                    );
                }
            }
        }

        return $this->renderAjax('@frontend/views/templates/control/blocks/basket/_basket-product-count');
    }

    public function actionUpdateItem($alias_menu_item, $document_id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect('/');
        }

        $parentData = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where(['alias' => 'basket'])
            ->one();

        $modelDocumentForm = new DocumentForm();
        $modelDocumentForm->scenario = 'update-element';
        $modelDocumentForm->template_id = $parentData['template_id'];
        $modelDocumentForm->load(Yii::$app->request->post());
        $modelDocumentForm->validate();

        $item = (new \yii\db\Query())
            ->select(['value_numeric.id AS id_item', 'value_numeric.value AS count_items', 'value_price.discount_price AS price_items', 'value_price.currency AS currency', 'value_price.item_max AS item_max'])
            ->from('document')
            ->innerJoin('value_numeric', 'value_numeric.document_id = document.id')
            ->innerJoin('value_price', 'value_price.document_id = document.item_id')
            ->where([
                'document.id' => $document_id,
            ])
            ->one();

        if ($item && $modelDocumentForm->items_number && $modelDocumentForm->items_number <= $item['item_max']) {
            Yii::$app->db->createCommand()->update('value_numeric', ['value' => $modelDocumentForm->items_number], ['id' => $item['id_item']])->execute();
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'icon' => 'glyphicon glyphicon-ok',
                    'message' => Yii::t('app', 'Успешно'),
                ]
            );
        } else {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'danger',
                    'icon' => 'glyphicon glyphicon-ban',
                    'message' => Yii::t('app', 'Ошибка'),
                ]
            );
        }

        return $this->renderAjax('@frontend/views/templates/control/index', [
            'alias_menu_item' => $alias_menu_item
        ]);
    }

    public function actionUpdateCount()
    {
        return $this->renderAjax('@frontend/views/templates/control/blocks/basket/_basket-product-count');
    }

    /**
     * Удаления документа
     *
     * @return string
     * @throws ErrorException
     */
    public function actionDeleteItem($alias_menu_item, $document_id)
    {
        if (!Yii::$app->request->isPjax) {
            return $this->redirect('/');
        }

        $modelDocumentForm = DocumentForm::findOne($document_id);

        if ($modelDocumentForm->template->mark == 'basket') {
            try {
                $modelDocumentForm->delete();
            } catch (StaleObjectException $e) {
                Yii::$app->errorHandler->logException($e);
                throw new ErrorException($e->getMessage());
            } catch (\Throwable $e) {
                Yii::$app->errorHandler->logException($e);
                throw new ErrorException($e->getMessage());
            }

            Yii::$app->session->set(
                'message',
                [
                    'type' => 'success',
                    'icon' => 'glyphicon glyphicon-ok',
                    'message' => Yii::t('app', 'Успешно'),
                ]
            );
        } else {
            Yii::$app->session->set(
                'message',
                [
                    'type' => 'danger',
                    'icon' => 'glyphicon glyphicon-ban',
                    'message' => Yii::t('app', 'Ошибка'),
                ]
            );
        }

        return $this->renderAjax('@frontend/views/templates/control/index', [
            'alias_menu_item' => $alias_menu_item
        ]);
    }
}