<?php
/**
 * User: Vladimir Baranov <phpnt@yandex.ru>
 * Git: <https://github.com/phpnt>
 * VK: <https://vk.com/phpnt>
 * Date: 02.02.2019
 * Time: 13:09
 */

namespace common\widgets\TemplateOfElement\fields;

use phpnt\ICheck\ICheckAsset;
use Yii;
use yii\bootstrap\Html;
use yii\bootstrap\InputWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class FieldCheckbox extends InputWidget
{
    const TYPE_CHECBOX      = 'checkbox';
    const TYPE_CHECBOX_LIST = 'checkbox-list';
    const TYPE_RADIO        = 'radio';
    const TYPE_RADIO_LIST   = 'radio-list';

    const STYLE_MIMIMAL     = 'minimal';
    const STYLE_SQUARE      = 'square';
    const STYLE_FLAT        = 'flat';
    const STYLE_LINE        = 'line';
    const STYLE_POLARIS     = 'polaris';
    const STYLE_FUTURICO    = 'futurico';

    public $modelFieldForm;
    public $options = [];

    public $type;
    public $style;
    public $color           = 'black';
    public $items           = [];
    public $checkOptions    = [];

    private $idItem;

    public function init()
    {
        parent::init();

        $this->type = $this->type ? $this->type : 'checkbox';
        if ($this->style == 'polaris') {
            $this->color = 'polaris';
        }
        if ($this->style == 'futurico') {
            $this->color = 'futurico';
        }
        $this->color = ($this->color != 'black') ? $this->color : 'minimal';
        $this->options += [
            'class' => 'i-checks-'.$this->id
        ];

        $fieldID = $this->modelFieldForm->id;
        $this->idItem = 'field-' . $fieldID;
    }

    public function run()
    {
        $this->registerScript();

        /* @var $fieldsManage \common\widgets\TemplateOfElement\components\FieldsManage */
        $fieldsManage = Yii::$app->fieldsManage;

        $formName = $this->model->formName();
        $fieldID = $this->modelFieldForm->id;

        $value = isset($this->model->elements_fields[$this->modelFieldForm->id][0]) ? $this->model->elements_fields[$this->modelFieldForm->id][0] : $fieldsManage->getValue($this->modelFieldForm->id, $this->modelFieldForm->type, $this->model->id);

        $options = [
            'id' => 'field-' . $fieldID,
            'name' => $formName . "[elements_fields][$fieldID][0]",
            'value' => $value,
        ];

        $this->options = ArrayHelper::merge($this->options, $options);

        $this->field->label(Yii::t('app', $this->modelFieldForm->name));

        if (isset($this->model->errors_fields[$this->modelFieldForm->id][0])) {
            $error = $this->model->errors_fields[$this->modelFieldForm->id][0];
            $view = $this->getView();
            $view->registerJs('addError("#group-' .  $this->modelFieldForm->id . '", "' . $error . '");');
        }

        echo Html::activeCheckboxList($this->model, $this->attribute, $this->modelFieldForm->list, $this->options);
    }

    public function registerScript()
    {
        $view = $this->getView();
        $asset = ICheckAsset::register($view);
        $this->getCheckOptions();

        $view->registerCssFile($asset->baseUrl.'/skins/'.$this->style.'/'.$this->color.'.css');

        $this->checkOptions = Json::encode($this->checkOptions);

        $js = <<< JS
            $(document).ready(function(){
             if ('$this->style' == 'line') {
                 if ('$this->color' == 'line') {
                 $('#$this->idItem > input').each(function(){
                     console.log('ssssss');
                     var self = $(this),
                     label = self.next(),
                     label_text = label.text();
                     label.remove();
                     self.iCheck({
                         checkboxClass: 'icheckbox_line',
                         radioClass: 'iradio_line',
                         insert: '<div class="icheck_line-icon"></div>' + label_text
                     });
                  });
                 } else {
                     console.log('ffffff');
                     $('#$this->idItem > input').each(function(){
                        var self = $(this),
                          label = self.next(),
                          label_text = label.text();
                    
                        label.remove();
                        self.iCheck({
                          checkboxClass: 'icheckbox_line-$this->color',
                          radioClass: 'iradio_line-$this->color',
                          insert: '<div class="icheck_line-icon"></div>' + label_text
                        });
                 });
                 }
              } 
            if ('$this->style' == 'minimal' || '$this->style' == 'square' || '$this->style' == 'flat' || '$this->style' == 'polaris' || '$this->style' == 'futurico') {
                $(document).ready(function () {
                    $(".i-checks-$this->id").iCheck($this->checkOptions);
                }); 
            }              
        });
JS;
        $view->registerJs($js);
    }

    private function getCheckOptions() {
        switch ($this->style) {
            case 'minimal':
                if ($this->color == 'minimal') {
                    $this->checkOptions += [
                        'checkboxClass' => 'icheck-item icheckbox_'.$this->style.'',
                        'radioClass' => 'iradio_'.$this->style.'',
                    ];
                } else {
                    $this->checkOptions += [
                        'checkboxClass' => 'icheck-item icheckbox_'.$this->style.'-'.$this->color,
                        'radioClass' => 'iradio_'.$this->style.'-'.$this->color,
                    ];
                }
                break;
            case 'square':
                if ($this->color == 'square') {
                    $this->checkOptions += [
                        'checkboxClass' => 'icheck-item icheckbox_'.$this->style.'',
                        'radioClass' => 'iradio_'.$this->style.'',
                    ];
                } else {
                    $this->checkOptions += [
                        'checkboxClass' => 'icheck-item icheckbox_'.$this->style.'-'.$this->color,
                        'radioClass' => 'iradio_'.$this->style.'-'.$this->color,
                    ];
                }
                break;
            case 'flat':
                if ($this->color == 'flat') {
                    $this->checkOptions += [
                        'checkboxClass' => 'icheck-item icheckbox_'.$this->style.'',
                        'radioClass' => 'iradio_'.$this->style.'',
                    ];
                } else {
                    $this->checkOptions += [
                        'checkboxClass' => 'icheck-item icheckbox_'.$this->style.'-'.$this->color,
                        'radioClass' => 'iradio_'.$this->style.'-'.$this->color,
                    ];
                }
                break;
            case 'line':
                if ($this->color == 'line') {
                    $this->checkOptions += [
                        'checkboxClass' => 'icheck-item icheckbox_'.$this->style.'',
                        'radioClass' => 'iradio_'.$this->style.'',
                    ];
                } else {
                    $this->checkOptions += [
                        'checkboxClass' => 'icheck-item icheckbox_'.$this->style.'-'.$this->color,
                        'radioClass' => 'iradio_'.$this->style.'-'.$this->color,
                    ];
                }
                break;
            case 'polaris':
                $this->checkOptions += [
                    'checkboxClass' => 'icheck-item icheckbox_'.$this->style.'',
                    'radioClass' => 'iradio_'.$this->style.'',
                ];
                break;
            case 'futurico':
                $this->checkOptions += [
                    'checkboxClass' => 'icheck-item icheckbox_'.$this->style.'',
                    'radioClass' => 'iradio_'.$this->style.'',
                ];
                break;
        }
    }
}