<?php

namespace common\models\search;

use Yii;
use common\models\Constants;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\forms\DocumentForm;
use yii\db\ActiveQuery;

/**
 * DocumentSearch represents the model behind the search form of `common\models\forms\DocumentForm`.
 */
class DocumentSearch extends DocumentForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'is_folder', 'parent_id', 'child_id', 'template_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'position', 'access'], 'integer'],
            [['name', 'alias', 'title', 'meta_keywords', 'meta_description', 'annotation', 'content', 'ip', 'user_agent'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = DocumentForm::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'is_folder' => $this->is_folder,
            'parent_id' => $this->parent_id,
            'child_id' => $this->child_id,
            'template_id' => $this->template_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'position' => $this->position,
            'access' => $this->access,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'annotation', $this->annotation])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }

    /**
     * Поиск только элементов папки
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchElement($params)
    {
        $query = DocumentForm::find()
            ->where(['is_folder' => null]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if ($this->elements_fields) {
            if ($this->validateFields()) {
                $query = $this->setSearchFieldsQuery($query);
            }
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'is_folder' => $this->is_folder,
            'parent_id' => $this->parent_id,
            'child_id' => $this->child_id,
            'template_id' => $this->template_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'position' => $this->position,
            'access' => $this->access,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'annotation', $this->annotation])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }

    /**
     * Поиск только элементов папки
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchBasketElements($params, $products)
    {
        $query = DocumentForm::find()
            ->where([
                'id' => $products,
                'is_folder' => null,
            ]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if ($this->elements_fields) {
            if ($this->validateFields()) {
                $query = $this->setSearchFieldsQuery($query);
            }
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'is_folder' => $this->is_folder,
            'parent_id' => $this->parent_id,
            'child_id' => $this->child_id,
            'template_id' => $this->template_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'position' => $this->position,
            'access' => $this->access,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'meta_keywords', $this->meta_keywords])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description])
            ->andFilterWhere(['like', 'annotation', $this->annotation])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }

    /* @var $query ActiveQuery */
    public function setSearchFieldsQuery($query) {
        foreach ($this->elements_fields as $key => $forms_field) {
            $field = (new \yii\db\Query())
                ->select(['*'])
                ->from('field')
                ->where(['id' => $key])
                ->one();

            if (is_array($forms_field)) {
                if (($field['type'] == Constants::FIELD_TYPE_INT || $field['type'] == Constants::FIELD_TYPE_DISCOUNT) &&
                    ($this->elements_fields[$key][0] != '' ||
                        $this->elements_fields[$key][1] != '')) {
                    $query->leftJoin('value_int AS int' . $key, 'document.id = int' . $key . '.document_id');
                    if (($this->elements_fields[$key][0] != '' &&
                            $this->elements_fields[$key][1] != '') &&
                        $this->elements_fields[$key][0] > $this->elements_fields[$key][1]) {
                        $buffer = $this->elements_fields[$key][0];
                        $this->elements_fields[$key][0] = $this->elements_fields[$key][1];
                        $this->elements_fields[$key][1] = $buffer;
                    }
                    // если значеине "от" не пустое
                    if ($this->elements_fields[$key][0] != '') {
                        $query->andWhere(['and',
                            ['int' . $key . '.type' => $field['type']],
                            ['>=', 'int' . $key . '.value', $this->elements_fields[$key][0]]
                        ]);
                    }
                    // если значеине "до" не пустое
                    if ($this->elements_fields[$key][1] != '') {
                        $query->andWhere(['and',
                            ['int' . $key . '.type' => $field['type']],
                            ['<=', 'int' . $key . '.value', $this->elements_fields[$key][1]]
                        ]);
                    }
                }
                if ($field['type'] == Constants::FIELD_TYPE_CHECKBOX) {
                    if ($this->elements_fields[$key][0] != '') {
                        $query->leftJoin('value_int AS checkbox' . $key, 'document.id = checkbox' . $key . '.document_id');
                        $query->andWhere(['and',
                            ['checkbox' . $key . '.type' => Constants::FIELD_TYPE_CHECKBOX],
                            ['checkbox' . $key . '.title' => $field['name']],
                            ['checkbox' . $key . '.value' => $this->elements_fields[$key][0]]
                        ]);
                    }
                }
                if ($field['type'] == Constants::FIELD_TYPE_LIST_MULTY) {
                    if ($this->elements_fields[$key][0] != '') {
                        $query->leftJoin('value_int AS list_multy' . $key, 'document.id = list_multy' . $key . '.document_id');
                        $query->andWhere(['and',
                            ['list_multy' . $key . '.type' => Constants::FIELD_TYPE_LIST_MULTY],
                            ['list_multy' . $key . '.title' => $field['name']],
                            ['list_multy' . $key . '.value' => $this->elements_fields[$key][0]]
                        ]);
                    }
                }
                if ($field['type'] == Constants::FIELD_TYPE_RADIO) {
                    if ($this->elements_fields[$key][0] != '') {
                        $query->leftJoin('value_int AS radio' . $key, 'document.id = radio' . $key . '.document_id');
                        $query->andWhere(['and',
                            ['radio' . $key . '.type' => Constants::FIELD_TYPE_RADIO],
                            ['radio' . $key . '.title' => $field['name']],
                            ['radio' . $key . '.value' => $this->elements_fields[$key][0]]
                        ]);
                    }
                }
                if ($field['type'] == Constants::FIELD_TYPE_LIST) {
                    if ($this->elements_fields[$key][0] != '') {
                        $query->leftJoin('value_int AS list' . $key, 'document.id = list' . $key . '.document_id');
                        $query->andWhere(['and',
                            ['list' . $key . '.type' => Constants::FIELD_TYPE_LIST],
                            ['list' . $key . '.title' => $field['name']],
                            ['list' . $key . '.value' => $this->elements_fields[$key][0]]
                        ]);
                    }
                }
                if (($field['type'] == Constants::FIELD_TYPE_DATE) &&
                    ($this->elements_fields[$key][0] != '' ||
                        $this->elements_fields[$key][1] != '')) {
                    if ($field['type'] == Constants::FIELD_TYPE_DATE) {
                        $query->leftJoin('value_int AS date' . $key, 'document.id = date' . $key . '.document_id');
                        if (($this->elements_fields[$key][0] != '' &&
                                $this->elements_fields[$key][1] != '') &&
                            $this->elements_fields[$key][0] > $this->elements_fields[$key][1]) {
                            $buffer = $this->elements_fields[$key][0];
                            $this->elements_fields[$key][0] = $this->elements_fields[$key][1];
                            $this->elements_fields[$key][1] = $buffer;
                        }
                        // если значеине "от" не пустое
                        if ($this->elements_fields[$key][0] != '') {
                            $query->andWhere(['and',
                                ['date' . $key . '.type' => Constants::FIELD_TYPE_DATE],
                                ['>=', 'date' . $key . '.value', strtotime($this->elements_fields[$key][0])]
                            ]);
                        }
                        // если значеине "до" не пустое
                        if ($this->elements_fields[$key][1] != '') {
                            $query->andWhere(['and',
                                ['date' . $key . '.type' => Constants::FIELD_TYPE_DATE],
                                ['<=', 'date' . $key . '.value', strtotime($this->elements_fields[$key][1])]
                            ]);
                        }
                    }
                }
                if ($field['type'] == Constants::FIELD_TYPE_COUNTRY) {
                    if ($this->elements_fields[$key][0] != '') {
                        $query->leftJoin('value_int AS country' . $key, 'document.id = country' . $key . '.document_id');
                        $query->andWhere(['and',
                            ['country' . $key . '.type' => Constants::FIELD_TYPE_COUNTRY],
                            ['country' . $key . '.value' => $this->elements_fields[$key][0]]
                        ]);
                    }
                }
                if ($field['type'] == Constants::FIELD_TYPE_REGION) {
                    if ($this->elements_fields[$key][0] != '') {
                        $query->leftJoin('value_int AS region' . $key, 'document.id = region' . $key . '.document_id');
                        $query->andWhere(['and',
                            ['region' . $key . '.type' => Constants::FIELD_TYPE_REGION],
                            ['region' . $key . '.value' => $this->elements_fields[$key][0]]
                        ]);
                    }
                }
                if ($field['type'] == Constants::FIELD_TYPE_CITY) {
                    if ($this->elements_fields[$key][0] != '') {
                        $query->leftJoin('value_int AS city' . $key, 'document.id = city' . $key . '.document_id');
                        $query->andWhere(['and',
                            ['city' . $key . '.type' => Constants::FIELD_TYPE_CITY],
                            ['city' . $key . '.value' => $this->elements_fields[$key][0]]
                        ]);
                    }
                }
                if (($field['type'] == Constants::FIELD_TYPE_FLOAT) &&
                    ($this->elements_fields[$key][0] != '' ||
                        $this->elements_fields[$key][1] != '')) {
                    if ($field['type'] == Constants::FIELD_TYPE_FLOAT) {
                        $query->leftJoin('value_numeric AS float' . $key, 'document.id = float' . $key . '.document_id');
                        if (($this->elements_fields[$key][0] != '' &&
                                $this->elements_fields[$key][1] != '') &&
                            $this->elements_fields[$key][0] > $this->elements_fields[$key][1]) {
                            $buffer = $this->elements_fields[$key][0];
                            $this->elements_fields[$key][0] = $this->elements_fields[$key][1];
                            $this->elements_fields[$key][1] = $buffer;
                        }
                        // если значеине "от" не пустое

                        if ($this->elements_fields[$key][0] != '') {
                            $query->andWhere(['and',
                                ['float' . $key . '.type' => Constants::FIELD_TYPE_FLOAT],
                                ['>=', 'float' . $key . '.value', $this->elements_fields[$key][0]]
                            ]);
                        }
                        // если значеине "до" не пустое
                        if ($this->elements_fields[$key][1] != '') {
                            $query->andWhere(['and',
                                ['float' . $key . '.type' => Constants::FIELD_TYPE_FLOAT],
                                ['<=', 'float' . $key . '.value', $this->elements_fields[$key][1]]
                            ]);
                        }
                    }
                }
                if (($field['type'] == Constants::FIELD_TYPE_PRICE) &&
                    ($this->elements_fields[$key][0] != '' ||
                        $this->elements_fields[$key][1] != '')) {
                    if ($field['type'] == Constants::FIELD_TYPE_PRICE) {
                        $query->leftJoin('value_price AS price' . $key, 'document.id = price' . $key . '.document_id');
                        if (($this->elements_fields[$key][0] != '' &&
                                $this->elements_fields[$key][1] != '') &&
                            $this->elements_fields[$key][0] > $this->elements_fields[$key][1]) {
                            $buffer = $this->elements_fields[$key][0];
                            $this->elements_fields[$key][0] = $this->elements_fields[$key][1];
                            $this->elements_fields[$key][1] = $buffer;
                        }
                        // если значеине "от" не пустое

                        if ($this->elements_fields[$key][0] != '') {
                            $query->andWhere(['and',
                                ['price' . $key . '.type' => Constants::FIELD_TYPE_PRICE],
                                ['>=', 'price' . $key . '.discount_price', $this->elements_fields[$key][0]]
                            ]);
                        }
                        // если значеине "до" не пустое
                        if ($this->elements_fields[$key][1] != '') {
                            $query->andWhere(['and',
                                ['price' . $key . '.type' => Constants::FIELD_TYPE_PRICE],
                                ['<=', 'price' . $key . '.discount_price', $this->elements_fields[$key][1]]
                            ]);
                        }
                    }
                }
                if ($field['type'] == Constants::FIELD_TYPE_STRING) {
                    if ($this->elements_fields[$key][0] != '') {
                        $query->leftJoin('value_string AS string' . $key, 'document.id = string' . $key . '.document_id');
                        $query->andWhere(['and',
                            ['string' . $key . '.type' => Constants::FIELD_TYPE_STRING],
                            ['like', 'string' . $key . '.value', $this->elements_fields[$key][0]]
                        ]);
                    }
                }
            }
        }

        return $query;
    }

    /**
     * валидация полей шаблона для поиска
     *
     * @return boolean
     */
    public function validateFields() {
        foreach ($this->elements_fields as $key => $forms_field) {
            $field = (new \yii\db\Query())
                ->select(['*'])
                ->from('field')
                ->where(['id' => $key])
                ->one();

            if (is_array($forms_field)) {
                foreach ($forms_field as $sub_key => $item) {
                    // Проверка DOUBLE на число
                    if (($field['type'] == Constants::FIELD_TYPE_INT ||
                            $field['type'] == Constants::FIELD_TYPE_FLOAT ||
                            $field['type'] == Constants::FIELD_TYPE_PRICE) &&
                        $this->elements_fields[$key][$sub_key] != '') {
                        if (!is_numeric($this->elements_fields[$key][$sub_key])) {
                            $this->errors_fields[$key][$sub_key] = Yii::t('app', 'Поле не является числом.');
                        }
                    }
                }
            }
        }

        if ($this->errors_fields) {
            $this->addError('field_error', 'Ошибка поля шаблона');
            return false;
        }

        return true;
    }
}
