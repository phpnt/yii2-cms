<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\forms\UserOauthKeyForm;

/**
 * UserOauthKeySearch represents the model behind the search form of `common\models\forms\UserOauthKeyForm`.
 */
class UserOauthKeySearch extends UserOauthKeyForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'provider_id'], 'integer'],
            [['provider_user_id', 'page'], 'safe'],
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
        $query = UserOauthKeyForm::find();

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
            'user_id' => $this->user_id,
            'provider_id' => $this->provider_id,
        ]);

        $query->andFilterWhere(['like', 'provider_user_id', $this->provider_user_id])
            ->andFilterWhere(['like', 'page', $this->page]);

        return $dataProvider;
    }
}
