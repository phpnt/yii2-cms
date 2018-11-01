<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\forms\GeoCityForm;

/**
 * GeoCitySearch represents the model behind the search form of `common\models\forms\GeoCityForm`.
 */
class GeoCitySearch extends GeoCityForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_geo_city', 'id_geo_region'], 'integer'],
            [['name_ru', 'name_en', 'okato'], 'safe'],
            [['lat', 'lon'], 'number'],
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
        $query = GeoCityForm::find();

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
            'id_geo_city' => $this->id_geo_city,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'id_geo_region' => $this->id_geo_region,
        ]);

        $query->andFilterWhere(['like', 'name_ru', $this->name_ru])
            ->andFilterWhere(['like', 'name_en', $this->name_en])
            ->andFilterWhere(['like', 'okato', $this->okato]);

        return $dataProvider;
    }
}
