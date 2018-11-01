<?php
/**
 * Created by PhpStorm.
 * User: Баранов Владимир <phpnt@yandex.ru>
 * Date: 18.08.2018
 * Time: 19:25
 */

namespace common\models\extend;

use common\models\forms\ValueFileForm;
use Yii;
use common\models\Constants;
use common\models\Field;
use common\models\forms\TemplateForm;
use common\models\forms\ValueIntForm;
use common\models\forms\ValueNumericForm;
use common\models\forms\ValueStringForm;
use common\models\forms\ValueTextForm;
use yii\helpers\Json;

/*
 * @property array $typeList
 * @property array $typeItem
 * @property array $fileExtList
 * @property array $fileExtItem
 * @property array $fileExtValues
 * @property string $city
 * @property string $region
 * @property string $country
 *
 * @property TemplateForm $template
 * @property ValueFileForm[] $valueFiles
 * @property ValueIntForm[] $valueInts
 * @property ValueNumericForm[] $valueNumerics
 * @property ValueStringForm[] $valueStrings
 * @property ValueStringForm[] $valueStringsOfTemplate
 * @property ValueTextForm[] $valueTexts
 */
class FieldExtend extends Field
{
    /**
     * Возвращает массив выбранных расширений для файлов
     * @return array
     */
    public function getFileExtValues()
    {
        $params = Json::decode($this->params);
        return $params['file_extensions'];
    }
    /**
     * Возвращает город
     *
     * @return string
     */
    public function getCity($id_geo_city) {
        if ($id_geo_city) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_city')
                ->where(['id_geo_city' => $id_geo_city])
                ->one();
            return $data['name_ru'];
        }
        return '';
    }

    /**
     * Возвращает регион
     *
     * @return string
     */
    public function getRegion($id_geo_region) {
        if ($id_geo_region) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_region')
                ->where(['id_geo_region' => $id_geo_region])
                ->one();
            return $data['name_ru'];
        }
        return '';
    }

    /**
     * Возвращает страну
     *
     * @return string
    */
    public function getCountry($id_geo_country) {
        if ($id_geo_country) {
            $data = (new \yii\db\Query())
                ->select(['*'])
                ->from('geo_country')
                ->where(['id_geo_country' => $id_geo_country])
                ->one();
            return $data['name_ru'];
        }
        return '';
    }

    /**
     * Возвращает массив доступных расширений для файлов
     * @return array
     */
    public function getFileExtList()
    {
        return [
            Constants::FILE_EXT_JPEG =>  Yii::t('app', 'jpeg'),
            Constants::FILE_EXT_JPG =>  Yii::t('app', 'jpg'),
            Constants::FILE_EXT_PNG =>  Yii::t('app', 'png'),
            Constants::FILE_EXT_PSD =>  Yii::t('app', 'psd'),
            Constants::FILE_EXT_PDF =>  Yii::t('app', 'pdf'),
            Constants::FILE_EXT_DOC =>  Yii::t('app', 'doc'),
            Constants::FILE_EXT_DOCX =>  Yii::t('app', 'docx'),
            Constants::FILE_EXT_XLS =>  Yii::t('app', 'xls'),
            Constants::FILE_EXT_XLSX =>  Yii::t('app', 'xlsx'),
            Constants::FILE_EXT_TXT =>  Yii::t('app', 'txt'),
            Constants::FILE_EXT_MP3 =>  Yii::t('app', 'mp3'),
            Constants::FILE_EXT_WAV =>  Yii::t('app', 'wav'),
            Constants::FILE_EXT_AVI =>  Yii::t('app', 'avi'),
            Constants::FILE_EXT_MPG =>  Yii::t('app', 'mpg'),
            Constants::FILE_EXT_MPEG =>  Yii::t('app', 'mpeg'),
            Constants::FILE_EXT_MPEG_4 =>  Yii::t('app', 'mpeg_4'),
            Constants::FILE_EXT_DIVX =>  Yii::t('app', 'divx'),
            Constants::FILE_EXT_DJVU =>  Yii::t('app', 'djvu'),
            Constants::FILE_EXT_FB2 =>  Yii::t('app', 'fb2'),
            Constants::FILE_EXT_RAR =>  Yii::t('app', 'rar'),
            Constants::FILE_EXT_ZIP =>  Yii::t('app', 'zip'),
        ];
    }

    /**
     * Возвращает расширение файла
     *
     * @return string
     */
    public function getFileExtItem($extension = null)
    {
        if (!$extension) {
            $extension = $this->type;
        }

        switch ($extension) {
            case Constants::FILE_EXT_JPEG:
                return $this->fileExtList[Constants::FILE_EXT_JPEG];
                break;
            case Constants::FILE_EXT_JPG:
                return $this->fileExtList[Constants::FILE_EXT_JPG];
                break;
            case Constants::FILE_EXT_PNG:
                return $this->fileExtList[Constants::FILE_EXT_PNG];
                break;
            case Constants::FILE_EXT_PSD:
                return $this->fileExtList[Constants::FILE_EXT_PSD];
                break;
            case Constants::FILE_EXT_PDF:
                return $this->fileExtList[Constants::FILE_EXT_PDF];
                break;
            case Constants::FILE_EXT_DOC:
                return $this->fileExtList[Constants::FILE_EXT_DOC];
                break;
            case Constants::FILE_EXT_DOCX:
                return $this->fileExtList[Constants::FILE_EXT_DOCX];
                break;
            case Constants::FILE_EXT_XLS:
                return $this->fileExtList[Constants::FILE_EXT_XLS];
                break;
            case Constants::FILE_EXT_XLSX:
                return $this->fileExtList[Constants::FILE_EXT_XLSX];
                break;
            case Constants::FILE_EXT_TXT:
                return $this->fileExtList[Constants::FILE_EXT_TXT];
                break;
            case Constants::FILE_EXT_MP3:
                return $this->fileExtList[Constants::FILE_EXT_MP3];
                break;
            case Constants::FILE_EXT_WAV:
                return $this->fileExtList[Constants::FILE_EXT_WAV];
                break;
            case Constants::FILE_EXT_AVI:
                return $this->fileExtList[Constants::FILE_EXT_AVI];
                break;
            case Constants::FILE_EXT_MPG:
                return $this->fileExtList[Constants::FILE_EXT_MPG];
                break;
            case Constants::FILE_EXT_MPEG:
                return $this->fileExtList[Constants::FILE_EXT_MPEG];
                break;
            case Constants::FILE_EXT_MPEG_4:
                return $this->fileExtList[Constants::FILE_EXT_MPEG_4];
                break;
            case Constants::FILE_EXT_DIVX:
                return $this->fileExtList[Constants::FILE_EXT_DIVX];
                break;
            case Constants::FILE_EXT_DJVU:
                return $this->fileExtList[Constants::FILE_EXT_DJVU];
                break;
            case Constants::FILE_EXT_FB2:
                return $this->fileExtList[Constants::FILE_EXT_FB2];
                break;
            case Constants::FILE_EXT_RAR:
                return $this->fileExtList[Constants::FILE_EXT_RAR];
                break;
            case Constants::FILE_EXT_ZIP:
                return $this->fileExtList[Constants::FILE_EXT_ZIP];
                break;
        }
        return false;
    }

    /**
     * Возвращает массив возможных полей
     * @return array
     */
    public function getTypeList()
    {
        return [
            Constants::FIELD_TYPE_INT =>  Yii::t('app', 'Целое число'),
            Constants::FIELD_TYPE_INT_RANGE =>  Yii::t('app', 'Диапазон целых чисел'),
            Constants::FIELD_TYPE_FLOAT =>  Yii::t('app', 'Число с дробью'),
            Constants::FIELD_TYPE_FLOAT_RANGE =>  Yii::t('app', 'Диапазон чисел с дробью'),
            Constants::FIELD_TYPE_STRING =>  Yii::t('app', 'Строка'),
            Constants::FIELD_TYPE_TEXT =>  Yii::t('app', 'Текст'),
            Constants::FIELD_TYPE_CHECKBOX =>  Yii::t('app', 'Чекбокс'),
            Constants::FIELD_TYPE_RADIO =>  Yii::t('app', 'Радиокнопка'),
            Constants::FIELD_TYPE_LIST =>  Yii::t('app', 'Список'),
            Constants::FIELD_TYPE_LIST_MULTY =>  Yii::t('app', 'Список с мультивыбором'),
            Constants::FIELD_TYPE_PRICE =>  Yii::t('app', 'Цена'),
            Constants::FIELD_TYPE_DATE =>  Yii::t('app', 'Дата'),
            Constants::FIELD_TYPE_DATE_RANGE =>  Yii::t('app', 'Диапазон дат'),
            Constants::FIELD_TYPE_ADDRESS =>  Yii::t('app', 'Адрес'),
            Constants::FIELD_TYPE_CITY =>  Yii::t('app', 'Город'),
            Constants::FIELD_TYPE_REGION =>  Yii::t('app', 'Регион'),
            Constants::FIELD_TYPE_COUNTRY =>  Yii::t('app', 'Страна'),
            Constants::FIELD_TYPE_EMAIL =>  Yii::t('app', 'Эл. почта'),
            Constants::FIELD_TYPE_URL =>  Yii::t('app', 'Ссылка'),
            Constants::FIELD_TYPE_SOCIAL =>  Yii::t('app', 'Страница соц. сети'),
            Constants::FIELD_TYPE_YOUTUBE =>  Yii::t('app', 'Видео YouTube'),
            Constants::FIELD_TYPE_FILE =>  Yii::t('app', 'Файл'),
            Constants::FIELD_TYPE_FEW_FILES =>  Yii::t('app', 'Несколько файлов'),
        ];
    }

    /**
     * Возвращает тип поля
     *
     * @return string
     */
    public function getTypeItem()
    {
        switch ($this->type) {
            case Constants::FIELD_TYPE_INT:
                return $this->typeList[Constants::FIELD_TYPE_INT];
                break;
            case Constants::FIELD_TYPE_INT_RANGE:
                return $this->typeList[Constants::FIELD_TYPE_INT_RANGE];
                break;
            case Constants::FIELD_TYPE_FLOAT:
                return $this->typeList[Constants::FIELD_TYPE_FLOAT];
                break;
            case Constants::FIELD_TYPE_FLOAT_RANGE:
                return $this->typeList[Constants::FIELD_TYPE_FLOAT_RANGE];
                break;
            case Constants::FIELD_TYPE_STRING:
                return $this->typeList[Constants::FIELD_TYPE_STRING];
                break;
            case Constants::FIELD_TYPE_TEXT:
                return $this->typeList[Constants::FIELD_TYPE_TEXT];
                break;
            case Constants::FIELD_TYPE_CHECKBOX:
                return $this->typeList[Constants::FIELD_TYPE_CHECKBOX];
                break;
            case Constants::FIELD_TYPE_RADIO:
                return $this->typeList[Constants::FIELD_TYPE_RADIO];
                break;
            case Constants::FIELD_TYPE_LIST:
                return $this->typeList[Constants::FIELD_TYPE_LIST];
                break;
            case Constants::FIELD_TYPE_LIST_MULTY:
                return $this->typeList[Constants::FIELD_TYPE_LIST_MULTY];
                break;
            case Constants::FIELD_TYPE_PRICE:
                return $this->typeList[Constants::FIELD_TYPE_PRICE];
                break;
            case Constants::FIELD_TYPE_DATE:
                return $this->typeList[Constants::FIELD_TYPE_DATE];
                break;
            case Constants::FIELD_TYPE_DATE_RANGE:
                return $this->typeList[Constants::FIELD_TYPE_DATE_RANGE];
                break;
            case Constants::FIELD_TYPE_ADDRESS:
                return $this->typeList[Constants::FIELD_TYPE_ADDRESS];
                break;
            case Constants::FIELD_TYPE_CITY:
                return $this->typeList[Constants::FIELD_TYPE_CITY];
                break;
            case Constants::FIELD_TYPE_REGION:
                return $this->typeList[Constants::FIELD_TYPE_REGION];
                break;
            case Constants::FIELD_TYPE_COUNTRY:
                return $this->typeList[Constants::FIELD_TYPE_COUNTRY];
                break;
            case Constants::FIELD_TYPE_EMAIL:
                return $this->typeList[Constants::FIELD_TYPE_EMAIL];
                break;
            case Constants::FIELD_TYPE_URL:
                return $this->typeList[Constants::FIELD_TYPE_URL];
                break;
            case Constants::FIELD_TYPE_SOCIAL:
                return $this->typeList[Constants::FIELD_TYPE_SOCIAL];
                break;
            case Constants::FIELD_TYPE_YOUTUBE:
                return $this->typeList[Constants::FIELD_TYPE_YOUTUBE];
                break;
            case Constants::FIELD_TYPE_FILE:
                return $this->typeList[Constants::FIELD_TYPE_FILE];
                break;
            case Constants::FIELD_TYPE_FEW_FILES:
                return $this->typeList[Constants::FIELD_TYPE_FEW_FILES];
                break;
        }
        return false;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueStringsOfTemplate()
    {
        return $this->hasMany(ValueStringForm::className(), ['field_id' => 'id'])->where(['document_id' => null]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(TemplateForm::className(), ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueFiles()
    {
        return $this->hasMany(ValueFileForm::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueInts()
    {
        return $this->hasMany(ValueIntForm::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueNumerics()
    {
        return $this->hasMany(ValueNumericForm::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueStrings()
    {
        return $this->hasMany(ValueStringForm::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValueTexts()
    {
        return $this->hasMany(ValueTextForm::className(), ['field_id' => 'id']);
    }
}