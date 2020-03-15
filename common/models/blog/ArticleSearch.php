<?php

namespace common\models\blog;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * ArticleSearch represents the model behind the search form of `common\models\blog\Article`.
 */
class ArticleSearch extends Article
{
    /** @var string|array */
    public $tag_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'created_date', 'updated_date'], 'integer'],
            [['title', 'description', 'content', 'tag_id'], 'safe'],
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
        $query = Article::find();

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

        //INDEX conditions
        $query->andFilterWhere([
            'id'      => $this->id,
            'user_id' => $this->user_id,
        ]);

        if (!empty($this->tag_id)) {
            $query->join('JOIN', 'tag2article', 'tag2article.article_id = article.id');
            $query->andWhere(['tag_id' => $this->tag_id]);
        }

        //NUMBER conditions
        $query->andFilterWhere([
            'status'       => $this->status,
            'created_date' => $this->created_date,
            'updated_date' => $this->updated_date,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }

    /**
     * @param null|array|Tag $attributes
     *
     * @return string
     */
    public static function url($attributes = null): string
    {
        if ($attributes instanceof Tag) {
            $k = Html::getInputName(new self(), 'tag_id');
            $attributes = [$k => $attributes->id];
        }
        $attributes[0] = '/blog/article/index';

        return Url::to($attributes);
    }
}
