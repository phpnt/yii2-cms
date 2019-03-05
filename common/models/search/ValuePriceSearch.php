<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\forms\ValuePriceForm;

/**
 * ValuePriceSearch represents the model behind the search form of `common\models\forms\ValuePriceForm`.
 */
class ValuePriceSearch extends ValuePriceForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'item_max', 'item_store', 'item_measure', 'type', 'document_id', 'field_id', 'discount_id'], 'integer'],
            [['title', 'currency', 'params'], 'safe'],
            [['price', 'discount_price', 'item'], 'number'],
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
        $query = ValuePriceForm::find();

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
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'item' => $this->item,
            'item_max' => $this->item_max,
            'item_store' => $this->item_store,
            'item_measure' => $this->item_measure,
            'type' => $this->type,
            'document_id' => $this->document_id,
            'field_id' => $this->field_id,
            'discount_id' => $this->discount_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'params', $this->params]);

        return $dataProvider;
    }
}
