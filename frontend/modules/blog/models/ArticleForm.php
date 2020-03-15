<?php
namespace frontend\modules\blog\models;

use common\models\blog\Article;
use common\models\blog\Tag;
use common\models\blog\Tag2Article;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Signup form
 */
class ArticleForm extends Article
{
    private const LIMIT_ALL_TAGS_CHAR = 300;

    public $tagList;

    private $_isNewRecord;
    private $aTagParsed;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->user_id = Yii::$app->user->id;
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['tagList', 'trim'],
            ['tagList', 'required'],
            ['tagList', 'string', 'max' => self::LIMIT_ALL_TAGS_CHAR],
            ['tagList', $this->fnValidateTagList()],
        ]);
    }

    public function attributeHints()
    {
        return [
            'tagList' => Yii::t('app', 'Tags should be separated with space.'),
        ];
    }

    public function afterFind()
    {
        $this->tagList = $this->populateTagList();
        parent::afterFind();
    }

    public function beforeSave($insert)
    {
        $this->_isNewRecord = $this->isNewRecord;
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $this->saveTags();
        parent::afterSave($insert, $changedAttributes);
    }

    private function populateTagList(): string
    {
        $aTag = ArrayHelper::getColumn($this->tags, 'content');
        sort($aTag);
        return implode(' ', $aTag);
    }

    private function saveTags(): void
    {
        $aTagId = Tag::insertMany($this->aTagParsed);

        if (!$this->_isNewRecord) {
            Tag2Article::deleteAll(['article_id' => $this->id]);
        }

        Tag2Article::insertMany($this->id, $aTagId);
    }

    private function fnValidateTagList(): callable
    {
        return function () {
            $aTagRaw          = explode(' ', $this->tagList);
            $this->aTagParsed = [];

            foreach ($aTagRaw as $v) {
                $tag = trim($v);
                if (!$tag) {
                    continue;
                }

                $this->aTagParsed[] = $tag;

                $m = new Tag();
                $m->content = $tag;
                if (!$m->validate()) {
                    foreach ($m->getErrorSummary(true) as $error) {
                        $error = "Wrong tag \"$tag\": $error";
                        $this->addError('tagList', $error);
                    }
                }
            }
        };
    }
}
