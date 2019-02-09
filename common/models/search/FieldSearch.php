<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\forms\FieldForm;

/**
 * FieldSearch represents the model behind the search form of `common\models\forms\FieldForm`.
 */
class FieldSearch extends FieldForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'is_required', 'is_unique', 'min_str', 'max_str', 'template_id', 'use_filter'], 'integer'],
            [['name', 'error_required', 'error_unique', 'error_value', 'error_length', 'params', 'mask', 'hint'], 'safe'],
            [['min_val', 'max_val'], 'number'],
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
        $query = FieldForm::find();

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
            'type' => $this->type,
            'is_required' => $this->is_required,
            'is_unique' => $this->is_unique,
            'min_val' => $this->min_val,
            'max_val' => $this->max_val,
            'min_str' => $this->min_str,
            'max_str' => $this->max_str,
            'template_id' => $this->template_id,
            'use_filter' => $this->use_filter,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'error_required', $this->error_required])
            ->andFilterWhere(['like', 'error_unique', $this->error_unique])
            ->andFilterWhere(['like', 'error_value', $this->error_value])
            ->andFilterWhere(['like', 'error_length', $this->error_length])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'mask', $this->mask])
            ->andFilterWhere(['like', 'hint', $this->hint]);

        return $dataProvider;
    }
}
