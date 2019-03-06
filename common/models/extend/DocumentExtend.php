<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:24
 */

namespace common\models\extend;

use common\models\forms\ValueFileForm;
use common\models\forms\ValueIntForm;
use common\models\forms\ValuePriceForm;
use common\widgets\Basket\BasketButton;
use common\widgets\Basket\BasketManage;
use common\widgets\Carousel\Carousel;
use common\widgets\Comment\Comment;
use common\widgets\Rating\Rating;
use phpnt\youtube\YouTubeWidget;
use Yii;
use common\models\Constants;
use common\models\Document;
use common\models\forms\DocumentForm;
use common\models\forms\LikeForm;
use common\models\forms\TemplateForm;
use common\models\forms\UserForm;
use common\models\forms\ValueNumericForm;
use common\models\forms\ValueStringForm;
use common\models\forms\ValueTextForm;
use common\models\forms\VisitForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * @property array $rootDocuments
 * @property string $folder
 * @property array $templatesList
 * @property array $parentsList
 * @property array $positionsList
 * @property array $statusList
 * @property array $statusItem
 * @property array $currencyList
 * @property array $allFolders
 * @property array $accessList
 * @property int $viewedDocument
 * @property int $likedDocument
 * @property string $dataItem
 * @property array $discountsAvaible
 * @property array $measuresList
 *
 * @property DocumentForm $parent
 * @property DocumentForm[] $items
 * @property DocumentForm $item
 * @property DocumentForm $child
 * @property DocumentForm[] $childs
 * @property DocumentForm[] $documents
 * @property TemplateForm $template
 * @property UserForm $createdBy
 * @property UserForm $updatedBy
 * @property LikeForm[] $likes
 * @property ValueFileForm[] $valueFiles
 * @property ValueIntForm[] $valueInts
 * @property ValueNumericForm[] $valueNumerics
 * @property ValueStringForm[] $valueStrings
 * @property ValueTextForm[] $valueTexts
 * @property ValuePriceForm[] $valuePrices
 * @property ValuePriceForm[] $discounts
 * @property VisitForm[] $visits
 *
*/
class DocumentExtend extends Document
{
    /**
     * Возвращает доступные меры измерения
     * @return array
     */
    public function getMeasuresList()
    {
        return [
            Constants::ITEM_MEASURE_THING =>  Yii::t('app', 'шт'),
            Constants::ITEM_MEASURE_MM =>  Yii::t('app', 'мм'),
            Constants::ITEM_MEASURE_CM =>  Yii::t('app', 'см'),
            Constants::ITEM_MEASURE_M =>  Yii::t('app', 'м'),
            Constants::ITEM_MEASURE_MM2 =>  Yii::t('app', 'мм²'),
            Constants::ITEM_MEASURE_CM2 =>  Yii::t('app', 'см²'),
            Constants::ITEM_MEASURE_M2 =>  Yii::t('app', 'м²'),
            Constants::ITEM_MEASURE_MG =>  Yii::t('app', 'мг'),
            Constants::ITEM_MEASURE_G =>  Yii::t('app', 'г'),
            Constants::ITEM_MEASURE_KG =>  Yii::t('app', 'кг'),
            Constants::ITEM_MEASURE_T =>  Yii::t('app', 'т'),
            Constants::ITEM_MEASURE_ML =>  Yii::t('app', 'мл'),
            Constants::ITEM_MEASURE_L =>  Yii::t('app', 'л'),
            Constants::ITEM_MEASURE_M3 =>  Yii::t('app', 'м³'),
        ];
    }

    /**
     * Возвращает доступные акции и скидки
     * @return array
     */
    public function getDiscountsAvaible()
    {
        $discountForlder = (new \yii\db\Query())
            ->select(['*'])
            ->from('document')
            ->where([
                'alias' => 'discounts',
            ])
            ->one();

        if ($discountForlder) {
            /* @var $parent self */
            $discountsAvaible = (new \yii\db\Query())
                ->select(['document.id', 'document.name'])
                ->from('document')
                ->leftJoin('value_int', 'document.id = value_int.document_id')
                ->where([
                    'parent_id' => $discountForlder['id'],
                    'status' => Constants::STATUS_DOC_ACTIVE,
                ])
                ->andWhere([
                    'value_int.type' => Constants::FIELD_TYPE_DATE
                ])
                ->andWhere([
                    '>=', 'value_int.value', time()
                ])
                ->all();
            if ($discountsAvaible) {
                return ArrayHelper::map($discountsAvaible, 'id', 'name');
            }
        }

        return [];
    }

    /**
     * Возвращает сформированный шаблон элемента
     * @return int
     */
    public function getDataItem ()
    {
        /* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
        $fieldsManage = Yii::$app->fieldsManage;
        $view = $this->template->templateViewItem->view;
        $templateData = $fieldsManage->getData($this->id, $this->template_id);
        if ($templateData) {
            return $this->genereteView($templateData, $view, $type = Constants::TYPE_ITEM);
        }

        return Yii::t('app', 'Данных не найдено.');
    }

    /**
     * Возвращает сформированный шаблон элемента в списке
     * @return int
     */
    public function getDataItemList($url = null)
    {
        /* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
        $fieldsManage = Yii::$app->fieldsManage;
        $view = $this->template->templateViewItemList->view;
        $templateData = $fieldsManage->getData($this->id, $this->template_id);
        if ($templateData) {
            if ($templateData) {
                $view = $this->genereteView($templateData, $view, $type = Constants::TYPE_ITEM_LIST);
                $view = $this->generateIncludesField('{!item-view!}', $url, $view);
                return $view;
            }
        }

        return Yii::t('app', 'Данных не найдено.');
    }

    /**
     * Возвращает сформированный шаблон элемента в корзине
     * @return int
     */
    public function getDataItemListBasket($url = null)
    {
        /* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
        $fieldsManage = Yii::$app->fieldsManage;
        $view = $this->template->templateViewItemBasket->view;
        $templateData = $fieldsManage->getData($this->id, $this->template_id);
        if ($templateData) {
            if ($templateData) {
                $view = $this->genereteView($templateData, $view, $type = Constants::TYPE_ITEM_LIST);
                $view = $this->generateIncludesField('{!item-view!}', $url, $view);
                return $view;
            }
        }

        return Yii::t('app', 'Данных не найдено.');
    }

    /*
     * @return string
     * */
    private function genereteView($templateData, $view, $type) {
        // Указан используется ли карусель
        if (strpos($view, '{^[') !== false) {
            $parsed = $this->getStringBetween($view, '{^[', ']^}');
            $carouselString = '{^[' . $parsed . ']^}';
            $carouselItems = explode(',', $parsed);
            $result = [];
            foreach ($carouselItems as $carouselItem) {
                $result[] = trim($carouselItem);
            }
            $carouselItems = $result;
        }
        foreach ($templateData as $field) {
            $view = str_replace('{_' . $field['title'] . '_}', Yii::t('app', $field['title']), $view);
            if ($field['type'] == Constants::FIELD_TYPE_INT || $field['type'] == Constants::FIELD_TYPE_FLOAT || $field['type'] == Constants::FIELD_TYPE_STRING ||
                $field['type'] == Constants::FIELD_TYPE_DATE || $field['type'] == Constants::FIELD_TYPE_LIST || $field['type'] == Constants::FIELD_TYPE_RADIO ||
                $field['type'] == Constants::FIELD_TYPE_TEXT || $field['type'] == Constants::FIELD_TYPE_ADDRESS ||
                $field['type'] == Constants::FIELD_TYPE_COUNTRY || $field['type'] == Constants::FIELD_TYPE_REGION || $field['type'] == Constants::FIELD_TYPE_CITY ||
                $field['type'] == Constants::FIELD_TYPE_EMAIL || $field['type'] == Constants::FIELD_TYPE_URL || $field['type'] == Constants::FIELD_TYPE_SOCIAL ||
                $field['type'] == Constants::FIELD_TYPE_DISCOUNT) {
                $field['value'] = $field['value'] ? Yii::t('app', $field['value']) : Yii::t('app', '(не задано)');
                if (isset($field['value'])) {
                    $view = str_replace('{=' . $field['title'] . '=}', Yii::t('app', $field['value']), $view);
                } else {
                    $view = str_replace('{=' . $field['title'] . '=}', Yii::t('app', '(не задано)'), $view);
                }
            } elseif ($field['type'] == Constants::FIELD_TYPE_PRICE) {
                if (isset($field['value'])) {
                    $view = str_replace('{=' . $field['title'] . '=}', $field['value']['discount_price'], $view);
                } else {
                    $view = str_replace('{=' . $field['title'] . '=}', Yii::t('app', '(не задано)'), $view);
                }
                // валюта
                if (strpos($view, '{$#' . $field['title'] . '#$}') !== false) {
                    if (isset($field['value']['currency'])) {
                        $view = str_replace('{$#' . $field['title'] . '#$}', $field['value']['currency'], $view);
                    } else {
                        $view = str_replace('{$#' . $field['title'] . '#$}', Yii::t('app', ''), $view);
                    }
                }
                // название скидки
                if (strpos($view, '{$_' . $field['title'] . '_$}') !== false) {
                    if (isset($field['value']['name'])) {
                        $view = str_replace('{$_' . $field['title'] . '_$}', $field['value']['name'], $view);
                    } else {
                        $view = str_replace('{$_' . $field['title'] . '_$}', Yii::t('app', ''), $view);
                    }
                }
                // цена без скидки
                if (strpos($view, '{$=' . $field['title'] . '=$}') !== false) {
                    if (isset($field['value']['price']) && $field['value']['price'] != $field['value']['discount_price']) {
                        $view = str_replace('{$=' . $field['title'] . '=$}', $field['value']['price'], $view);
                    } else {
                        $view = str_replace('{$=' . $field['title'] . '=$}', Yii::t('app', ''), $view);
                    }
                }
                // процент скидки
                if (strpos($view, '{$%' . $field['title'] . '%$}') !== false) {
                    if (isset($field['value']['percent'])) {
                        $view = str_replace('{$%' . $field['title'] . '%$}', $field['value']['percent'] . '%', $view);
                    } else {
                        $view = str_replace('{$%' . $field['title'] . '%$}', Yii::t('app', ''), $view);
                    }
                }
                // дата окончания скидки
                if (strpos($view, '{$!' . $field['title'] . '!$}') !== false) {
                    if (isset($field['value']['date_end'])) {
                        $view = str_replace('{$!' . $field['title'] . '!$}', $field['value']['date_end'], $view);
                    } else {
                        $view = str_replace('{$!' . $field['title'] . '!$}', Yii::t('app', ''), $view);
                    }
                }
                // ассортимент
                if (strpos($view, '{$~' . $field['title'] . '~$}') !== false) {
                    if (isset($field['value']['item'])) {
                        $view = str_replace('{$~' . $field['title'] . '~$}', $field['value']['item'], $view);
                    } else {
                        $view = str_replace('{$~' . $field['title'] . '~$}', Yii::t('app', ''), $view);
                    }
                }
                // мера измерения
                if (strpos($view, '{$^' . $field['title'] . '^$}') !== false) {
                    if (isset($field['value']['item_measure'])) {
                        $view = str_replace('{$^' . $field['title'] . '^$}', $this->getMeasuresList()[$field['value']['item_measure']], $view);
                    } else {
                        $view = str_replace('{$^' . $field['title'] . '^$}', Yii::t('app', ''), $view);
                    }
                }
                // количество на складе
                if (strpos($view, '{$?' . $field['title'] . '?$}') !== false) {
                    if (isset($field['value']['item_store'])) {
                        $view = str_replace('{$?' . $field['title'] . '?$}', $field['value']['item_store'], $view);
                    } else {
                        $view = str_replace('{$?' . $field['title'] . '?$}', Yii::t('app', ''), $view);
                    }
                }
                // Кнопка добавить в корзину
                if (strpos($view, '{!+basket+!}') !== false) {
                    $basketButton = BasketButton::widget([
                        'document_id' => $this->id,
                    ]);
                    $view = str_replace('{!+basket+!}', '<div class="basket">' . $basketButton . '</div>', $view);
                }
                // Количество выбранного товара
                if (strpos($view, '{$+' . $field['title'] . '+$}') !== false ||
                    strpos($view, '{$*' . $field['title'] . '*$}') !== false ||
                    strpos($view, '{!-basket-!}') !== false) {
                    if (Yii::$app->user->isGuest) {
                        $dataBasket = (new \yii\db\Query())
                            ->select(['value_numeric.value AS count_items', 'document.id'])
                            ->from('document')
                            ->innerJoin('value_numeric', 'value_numeric.document_id = document.id')
                            ->innerJoin('template', 'template.id = document.template_id')
                            ->where([
                                'document.item_id' => $field['value']['document_id'],
                                'value_numeric.type' => Constants::FIELD_TYPE_NUM,
                                'template.mark' => 'basket',
                                'ip' => Yii::$app->request->userIP,
                                'user_agent' => Yii::$app->request->userAgent,
                            ])
                            ->one();
                    } else {
                        $dataBasket = (new \yii\db\Query())
                            ->select(['value_numeric.value AS count_items', 'document.id'])
                            ->from('document')
                            ->innerJoin('value_numeric', 'value_numeric.document_id = document.id')
                            ->innerJoin('template', 'template.id = document.template_id')
                            ->where([
                                'document.item_id' => $field['value']['document_id'],
                                'value_numeric.type' => Constants::FIELD_TYPE_NUM,
                                'template.mark' => 'basket',
                                'created_by' => Yii::$app->user->id
                            ])
                            ->one();
                    }


                    $view = str_replace('{$+' . $field['title'] . '+$}', $dataBasket['count_items'], $view);

                    $fullPrice = $dataBasket['count_items'] * $field['value']['discount_price'];
                    $view = str_replace('{$*' . $field['title'] . '*$}', $fullPrice, $view);

                    if (strpos($view, '{!-basket-!}') !== false) {
                        $basketManage = BasketManage::widget([
                            'product_id' => $this->id,
                            'document_id' => $dataBasket['id'],
                        ]);
                        $view = str_replace('{!-basket-!}', '<div class="basket">' . $basketManage . '</div>', $view);
                    }
                }
            } elseif ($field['type'] == Constants::FIELD_TYPE_INT_RANGE || $field['type'] == Constants::FIELD_TYPE_FLOAT_RANGE || $field['type'] == Constants::FIELD_TYPE_DATE_RANGE) {
                if (isset($field['value'])) {
                    $view = str_replace('{=' . $field['title'] . '=}', $field['value'][0] . ' - ' . $field['value'][1], $view);
                } else {
                    $view = str_replace('{=' . $field['title'] . '=}', Yii::t('app', '(не задано)'), $view);
                }
            } elseif ($field['type'] == Constants::FIELD_TYPE_CHECKBOX || $field['type'] == Constants::FIELD_TYPE_LIST_MULTY) {
                if (isset($field['value'])) {
                    $string = '';
                    $i = 0;
                    foreach ($field['value'] as $value) {
                        if ($i == 0) {
                            $string .= Yii::t('app', $value);
                        } else {
                            $string .= ', ' . Yii::t('app', $value);
                        }
                        $i++;
                    }
                    $view = str_replace('{=' . $field['title'] . '=}', $string, $view);
                } else {
                    $view = str_replace('{=' . $field['title'] . '=}', Yii::t('app', '(не задано)'), $view);
                }
            } elseif ($field['type'] == Constants::FIELD_TYPE_FILE) {
                $url = Html::a($field['value']['name'], Url::to([$field['value']['path']]), ['target' => '_blank']);
                if (isset($field['value'])) {
                    $view = str_replace('{=' . $field['title'] . '=}', $url, $view);
                } else {
                    $view = str_replace('{=' . $field['title'] . '=}', Yii::t('app', '(не задано)'), $view);
                }

                // только для изображений
                if (($field['value']['extension'] == 'jpg' ||
                    $field['value']['extension'] == 'jpeg' ||
                    $field['value']['extension'] == 'png') ||
                    $field['value'] == null) {

                    // если файл не существуйт, назначаем картинку нет фото
                    $image = Yii::getAlias( '@frontend/web' . $field['value']['path']);
                    if(!file_exists($image) || $field['value'] == null) {
                        $field['value']['path'] = '/images/service/no-foto.png';
                    }
                    if (strpos($view, '{^_' . $field['title'] . '_^}') !== false) {
                        $image = Html::img($field['value']['path'], [
                            'class' => 'full-width',
                            'alt' => Yii::t('app', $field['title'])
                        ]);
                        $view = str_replace('{^_' . $field['title'] . '_^}', $image, $view);
                    }
                    if (strpos($view, '{^=' . $field['title'] . '=^}') !== false) {
                        if ($field['value']['path'] != '/images/service/no-foto.png') {
                            $image = Html::img($field['value']['path'], [
                                'class' => 'full-width cursor-pointer',
                                'alt' => Yii::t('app', $field['title']),
                                'onclick' => '
                                $.pjax({
                                    type: "GET",
                                    url: "' . Url::to(['/site/show-image', 'img' => $field['value']['path']]) . '",
                                    container: "#pjaxModalUniversal",
                                    push: false,
                                    timeout: 10000,
                                    scrollTo: false
                                })'
                            ]);
                        } else {
                            $image = Html::img($field['value']['path'], [
                                'class' => 'full-width',
                                'alt' => Yii::t('app', $field['title'])
                            ]);
                        }

                        $view = str_replace('{^=' . $field['title'] . '=^}', $image, $view);
                    }

                    // карусель
                    if (isset($carouselItems) && $carouselItems) {
                        if (isset($field['value'])) {
                            $keyItem = array_search($field['title'], $carouselItems);
                            if ($keyItem !== false) {
                                // если файл используется в карусели и он является картинкой добавляем его к массиву файлов
                                if (!isset($carouselFiles)) {
                                    $carouselFiles = [];
                                }

                                $image = Html::img($field['value']['path'], [
                                    'class' => 'full-width animated fadeIn'
                                ]);

                                $keyItem = array_search($image, $carouselFiles);
                                if ($keyItem === false) {
                                    $carouselFiles[] = $image;
                                }

                                unset($carouselItems[$keyItem]);
                            }
                        }
                    }
                }
            } elseif ($field['type'] == Constants::FIELD_TYPE_FEW_FILES) {
                if (isset($field['value'])) {
                    $string = '';
                    $i = 0;
                    foreach ($field['value'] as $value) {
                        if ($i == 0) {
                            $string .= Html::a($value['name'], Url::to([$value['path']]), ['target' => '_blank']);
                        } else {
                            $string .= ', ' . Html::a($value['name'], Url::to([$value['path']]), ['target' => '_blank']);
                        }
                        $i++;
                    }
                    $view = str_replace('{=' . $field['title'] . '=}', $string, $view);
                } else {
                    $view = str_replace('{=' . $field['title'] . '=}', Yii::t('app', '(не задано)'), $view);
                }

                // карусель
                if (isset($carouselItems) && $carouselItems) {
                    if ($field['value'] == null) {
                        $field['value'][] = [
                            'path' => '/images/service/no-foto.png',
                            'extension' => 'png'
                        ];
                    }
                    if (isset($field['value']) && $field['value']) {
                        $keyItem = array_search($field['title'], $carouselItems);
                        if ($keyItem !== false) {
                            foreach ($field['value'] as $file) {
                                if ($file['extension'] == 'jpg' ||
                                    $file['extension'] == 'jpeg' ||
                                    $file['extension'] == 'png') {

                                    // если файл не существуйт, назначаем картинку нет фото
                                    $image = Yii::getAlias( '@frontend/web' . $file['path']);
                                    if(!file_exists($image)) {
                                        $file['path'] = '/images/service/no-foto.png';
                                    }

                                    // если файл используется в карусели и он является картинкой добавляем его к массиву файлов
                                    if (!isset($carouselFiles)) {
                                        $carouselFiles = [];
                                    }

                                    $image = Html::img($file['path'], [
                                        'class' => 'full-width animated fadeIn'
                                    ]);

                                    $keyItem = array_search($image, $carouselFiles);
                                    if ($keyItem === false) {
                                        $carouselFiles[] = $image;
                                    }
                                }
                            }
                            unset($carouselItems[$keyItem]);
                        }
                    }
                }
            } elseif ($field['type'] == Constants::FIELD_TYPE_YOUTUBE) {
                if (isset($field['value'])) {
                    if (strpos($view, '{=' . $field['title'] . '=}') !== false) {
                        // если вывод только значения
                        $view = str_replace('{=' . $field['title'] . '=}', $field['value'], $view);
                    }
                    if (strpos($view, '{^_' . $field['title'] . '_^}') !== false) {
                        // если вывод превью видео
                        /* @var $youTubeData \phpnt\youtube\components\YouTubeData */
                        $youTubeData = Yii::$app->youTubeData;
                        $preview = $youTubeData->getPreview($field['value'], null, 'medium');
                        $image = Html::img($preview['url'], [
                            'class' => 'full-width'
                        ]);
                        $view = str_replace('{^_' . $field['title'] . '_^}', $image, $view);
                    }
                    if (strpos($view, '{^=' . $field['title'] . '=^}') !== false) {
                        // если вывод видео
                        $widget = YouTubeWidget::widget(['video_link' => $field['value']]);
                        $view = str_replace('{^=' . $field['title'] . '=^}', $widget, $view);
                    }
                } else {
                    $view = str_replace('{=' . $field['title'] . '=}', Yii::t('app', '(не задано)'), $view);
                }
            }
        }

        if (isset($carouselFiles) && $carouselFiles) {
            $view = str_replace($carouselString, Carousel::widget(['items' => $carouselFiles]), $view);
        }

        // Генерация значений встроенных полей у шаблона представления
        $view = $this->generateIncludesField('{~_Наименование_~}', $this->getAttributeLabel('name'), $view);
        $view = $this->generateIncludesField('{~=Наименование=~}', Yii::t('app', $this->name), $view);
        $view = $this->generateIncludesField('{~_Заголовок_~}', $this->getAttributeLabel('title'), $view);
        $view = $this->generateIncludesField('{~=Заголовок=~}', Yii::t('app', $this->title), $view);
        $view = $this->generateIncludesField('{~_Алиас_~}', $this->getAttributeLabel('alias'), $view);
        $view = $this->generateIncludesField('{~=Алиас=~}', Yii::t('app', $this->alias), $view);
        $view = $this->generateIncludesField('{~_Аннотация_~}', $this->getAttributeLabel('annotation'), $view);
        $view = $this->generateIncludesField('{~=Аннотация=~}', Yii::t('app', $this->annotation), $view);
        $view = $this->generateIncludesField('{~_Содержание_~}', $this->getAttributeLabel('content'), $view);
        $view = $this->generateIncludesField('{~=Содержание=~}', Yii::t('app', $this->content), $view);

        // проверка на служебные блоки
        if (strpos($view, '{!comments!}') !== false) {
            if ($this->template->add_comments && $type == Constants::TYPE_ITEM) {
                $comments = Comment::widget([
                    'document_id' => $this->id,
                    'access_answers' => true,   // разрешены ответы на комментарии
                ]);
                $view = str_replace('{!comments!}', '<div id="comment-widget">' . $comments . '</div>', $view);
            } else {
                $view = str_replace('{!comments!}', '', $view);
            }
        }

        if (strpos($view, '{!like!}') !== false) {
            // поиск блока рейтинга
            if ($this->template->add_rating) {
                $rating = Rating::widget([
                    'document_id' => $this->id,
                    'like' => true,             // показывать кнопку "Нравиться"
                    'dislike' => false,          // показывать кнопку "Не нравиться"
                    'percentage' => false,       // показывать процентный рейтинг
                    'stars_number' => 10,       // кол-во звезд в процентном рейтинге (от 2 до 10)
                    'access_guests' => true,    // разрешены не авторизованным пользователям
                ]);
                $view = str_replace('{!like!}', '<div id="rating-widget-' . $this->id . '">' . $rating . '</div>', $view);
            } else {
                $view = str_replace('{!like!}', '', $view);
            }
        } elseif (strpos($view, '{!like-dislike!}') !== false) {
            if ($this->template->add_rating) {
                $rating = Rating::widget([
                    'document_id' => $this->id,
                    'like' => true,             // показывать кнопку "Нравиться"
                    'dislike' => true,          // показывать кнопку "Не нравиться"
                    'percentage' => false,       // показывать процентный рейтинг
                    'stars_number' => 10,       // кол-во звезд в процентном рейтинге (от 2 до 10)
                    'access_guests' => true,    // разрешены не авторизованным пользователям
                ]);
                $view = str_replace('{!like-dislike!}', '<div id="rating-widget-' . $this->id . '">' . $rating . '</div>', $view);
            } else {
                $view = str_replace('{!like-dislike!}', '', $view);
            }
        } elseif (strpos($view, '{!stars!}') !== false) {
            if ($this->template->add_rating) {
                $rating = Rating::widget([
                    'document_id' => $this->id,
                    'like' => false,             // показывать кнопку "Нравиться"
                    'dislike' => false,          // показывать кнопку "Не нравиться"
                    'percentage' => true,       // показывать процентный рейтинг
                    'stars_number' => 10,       // кол-во звезд в процентном рейтинге (от 2 до 10)
                    'access_guests' => true,    // разрешены не авторизованным пользователям
                ]);
                $view = str_replace('{!stars!}', '<div id="rating-widget-' . $this->id . '">' . $rating . '</div>', $view);
            } else {
                $view = str_replace('{!stars!}', '', $view);
            }
        }

        if (strpos($view, '{!comments!}') !== false) {
            if ($this->template->add_comments && $type == Constants::TYPE_ITEM) {
                $comments = Comment::widget([
                    'document_id' => $this->id,
                    'access_answers' => true,   // разрешены ответы на комментарии
                ]);
                $view = str_replace('{!comments!}', '<div id="comment-widget">' . $comments . '</div>', $view);
            } else {
                $view = str_replace('{!comments!}', '', $view);
            }
        }

        return $view;
    }

    /* возвращает строку между символами $start, $end */
    private function getStringBetween($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /* Генерация значений встроенных полей у шаблона представления */
    private function generateIncludesField($name, $value, $view) {
        if (strpos($view, $name) !== false) {
            if ($this->name) {
                return str_replace($name, $value, $view);
            }
        }
        return str_replace($name, Yii::t('app', ''), $view);
    }

    /**
     * Возвращает количество лайков документа
     * @return int
     */
    public function getLikedDocument()
    {
        return (new \yii\db\Query())
            ->select(['*'])
            ->from('like')
            ->where([
                'document_id' => $this->id,
            ])
            ->count();
    }

    /**
     * Возвращает количество просмотров документа
     * @return int
     */
    public function getViewedDocument()
    {
        return (new \yii\db\Query())
            ->select(['*'])
            ->from('visit')
            ->where([
                'document_id' => $this->id,
            ])
            ->count();
    }

    /**
     * Возвращает упорядоченный массив всех папок
     * @return array
     */
    public function getAllFolders()
    {
        $manyDocumentForm = self::find()->where(['is_folder' => 1])
            ->orderBy(['position' => SORT_ASC])
            ->all();

        $data = [];

        foreach ($manyDocumentForm as $modelDocumentForm) {
            $data[$modelDocumentForm->id]['id'] = strval ($modelDocumentForm->id);
            $data[$modelDocumentForm->id]['parent'] = '#';
            $data[$modelDocumentForm->id]['text'] = Yii::t('app', $modelDocumentForm->name) . ' ' . $modelDocumentForm->statusItem;
            $data[$modelDocumentForm->id]['icon'] = 'fa fa-folder';
            $data[$modelDocumentForm->id]['state'] = [
                'opened' => $modelDocumentForm->id == 1 ? true : true
            ];
        }

        foreach ($manyDocumentForm as $modelDocumentForm) {
            if ($modelDocumentForm->parent_id) {
                $data[$modelDocumentForm->id]['parent'] = strval ($modelDocumentForm->parent_id);
            }
        }

        $result = [];
        foreach ($data as $item) {
            $result[] = $item;
        }

        return $result;
    }

    /**
     * Возвращает массив доступов
     * @return array
     */
    public function getAccessList()
    {
        return [
            Constants::ACCESS_USER =>  Yii::t('app', 'Авторизованный пользователь'),
            Constants::ACCESS_ALL => Yii::t('app', 'Все пользователи'),
            Constants::ACCESS_GUEST => Yii::t('app', 'Гости'),
        ];
    }

    /**
     * Возвращает массив статусов документа
     * @return array
     */
    public function getStatusList()
    {
        return [
            Constants::STATUS_DOC_WAIT =>  Yii::t('app', 'Не подтвержден'),
            Constants::STATUS_DOC_ACTIVE => Yii::t('app', 'Опубликован'),
            Constants::STATUS_DOC_BLOCKED => Yii::t('app', 'Заблокирован'),
        ];
    }

    /**
     * Возвращает статус документа
     */
    public function getStatusItem()
    {
        switch ($this->status) {
            case Constants::STATUS_DOC_WAIT:
                return '<i class="fas fa-hourglass-half"></i>';
                break;
            case Constants::STATUS_DOC_ACTIVE:
                return '<i class="fas fa-check"></i>';
                break;
            case Constants::STATUS_DOC_BLOCKED:
                return '<i class="fas fa-ban"></i>';
                break;
        }
        return false;
    }

    /**
     * Возвращает массив валют цены
     * @return array
     */
    public function getCurrencyList()
    {
        return [
            Constants::CURRENCY_RUB =>  Constants::CURRENCY_RUB,
            Constants::CURRENCY_USD => Constants::CURRENCY_USD,
            Constants::CURRENCY_EUR => Constants::CURRENCY_EUR,
        ];
    }

    /**
     * Возвращает папки и документы находящиеся в корне
     * @return array
     */
    public function getRootDocuments()
    {
        /* @var $parent self */
        $manyDocumentExtend = self::findAll([
            'parent_id' => null
        ]);

        return ArrayHelper::map($manyDocumentExtend, 'id', 'name');
    }

    /**
     * Возвращает папку, где находится элемент
     * @return string
     */
    public function getFolder()
    {
        /* @var $parent self */
        $parent = $this->parent;
        $string = '';

        while (isset($parent) && $parent) {
            $string .= $parent->name.'/';
            $parent = $parent->parent;
        }

        $folderArray = array_reverse (explode('/', $string));

        $string = '';
        foreach ($folderArray as $folder) {
            $string .= $folder.' / ';
        }

        return $string . Yii::t('app',
                $this->name . '<p><strong>({countFiles, plural, =0 {# документов} =1{# документ} one{# документ} few{# документа} many{# документов} other{# документа}})</strong></p>',
                ['countFiles' => count($this->childs)]);
    }

    /**
     * Возвращает список всех шаблонов
     * @return array
     */
    public function getTemplatesList()
    {
        $manyDocumentExtend = TemplateForm::find()
            ->where(['status' => Constants::STATUS_DOC_ACTIVE])
            ->all();

        return ArrayHelper::map($manyDocumentExtend, 'id', 'name');
    }

    /**
     * Возвращает список всех папок
     * @return array
     */
    public function getParentsList()
    {
        if ($this->id) {
            $manyDocumentExtend = self::find()
                ->where([
                    'is_folder' => 1
                ])
                ->andWhere(['!=', 'id', $this->id])
                ->all();
        } else {
            $manyDocumentExtend = self::find()
                ->where([
                    'is_folder' => 1
                ])
                ->all();
        }

        return ArrayHelper::map($manyDocumentExtend, 'id', 'name');
    }

    /**
     * Возвращает список всех папок, в текущем каталоге
     * @return array
     */
    public function getPositionsList()
    {
        if ($this->isNewRecord) {
            $folders = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'parent_id' => $this->parent_id,
                ])
                ->orderBy(['position' => SORT_ASC])
                ->all();
        } else {
            $folders = (new \yii\db\Query())
                ->select(['*'])
                ->from('document')
                ->where([
                    'parent_id' => $this->parent_id,
                ])
                ->andWhere(['!=', 'id', $this->id])
                ->orderBy(['position' => SORT_ASC])
                ->all();
        }

        return ArrayHelper::map($folders, 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(DocumentForm::class, ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(DocumentForm::class, ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(DocumentForm::class, ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChild()
    {
        return $this->hasOne(DocumentForm::class, ['parent_id' => 'id'])->where(['is_folder' => null]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChilds()
    {
        return $this->hasMany(DocumentForm::class, ['parent_id' => 'id'])->where(['is_folder' => null]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(DocumentForm::class, ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(TemplateForm::class, ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(UserForm::class, ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(UserForm::class, ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(LikeForm::class, ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueFiles()
    {
        return $this->hasMany(ValueFileForm::class, ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueInts()
    {
        return $this->hasMany(ValueIntForm::class, ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueNumerics()
    {
        return $this->hasMany(ValueNumericForm::class, ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueStrings()
    {
        return $this->hasMany(ValueStringForm::class, ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueTexts()
    {
        return $this->hasMany(ValueTextForm::class, ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValuePrices()
    {
        return $this->hasMany(ValuePriceForm::class, ['document_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDiscounts()
    {
        return $this->hasMany(ValuePriceForm::class, ['discount_id' => 'id']);        
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisits()
    {
        return $this->hasMany(VisitForm::class, ['document_id' => 'id']);
    }
}