<?php

namespace common\models\search;

use common\models\Message;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\forms\SourceMessageForm;

/**
 * SourceMessageSearch represents the model behind the search form of `common\models\forms\SourceMessageForm`.
 */
class SourceMessageSearch extends SourceMessageForm
{
    /**
     * @var SourceMessageSearch
     */
    protected static $_instance = null;

    /**
     * @var array
     */
    protected $locations = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    public $translation;

    /**
     * @return SourceMessageSearch
     */
    public static function getInstance()
    {
        if ( null === self::$_instance )
            self::$_instance = new self();

        return self::$_instance;
    }

    /**
     * @throws Exception
     */
    public function init()
    {
        if (!Yii::$app->has('i18n')) {
            throw new Exception('The i18n component does not exist');
        }

        $i18n = Yii::$app->i18n;
        $this->config = [
            'languages'             => $i18n->languages,
            'sourcePath'            => (is_string($i18n->sourcePath) ? [$i18n->sourcePath] : $i18n->sourcePath),
            'translator'            => $i18n->translator,
            'sort'                  => $i18n->sort,
            'removeUnused'          => $i18n->removeUnused,
            'only'                  => $i18n->only,
            'except'                => $i18n->except,
            'format'                => $i18n->format,
            'db'                    => $i18n->db,
            'messagePath'           => $i18n->messagePath,
            'overwrite'             => $i18n->overwrite,
            'catalog'               => $i18n->catalog,
            'messageTable'          => $i18n->messageTable,
            'sourceMessageTable'    => $i18n->sourceMessageTable,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'translation'], 'integer'],
            [['category', 'message', 'location', 'hash'], 'safe'],
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
        $query = SourceMessageForm::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if ($this->translation == '1') {
            $subQuery = Message::find()->select('id');
            $query->where(['not in', 'id', $subQuery]);
            /*$models = $query->all();
            dd($models);*/
        }

        //dd($this);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'message', $this->message])
            ->andFilterWhere(['like', 'location', $this->location])
            ->andFilterWhere(['like', 'hash', $this->hash]);;

        return $dataProvider;
    }
}
