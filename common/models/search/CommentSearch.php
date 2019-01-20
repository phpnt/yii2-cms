<?php

namespace common\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\forms\CommentForm;

/**
 * CommentSearch represents the model behind the search form of `common\models\forms\CommentForm`.
 */
class CommentSearch extends CommentForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'document_id', 'user_id', 'parent_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text', 'ip', 'user_agent'], 'safe'],
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
        $query = CommentForm::find();

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
            'document_id' => $this->document_id,
            'user_id' => $this->user_id,
            'parent_id' => $this->parent_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent]);

        return $dataProvider;
    }
}
