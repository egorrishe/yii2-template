<?php

namespace common\models\blog;

use common\traits\models\MyIsamTrait;
use Yii;

/**
 * This is the model class for table "tag".
 * ENGINE=MyISAM (due to performance)
 *
 * @property int $id
 * @property string|null $content
 *
 * @property Article[] $articles
 */
class Tag extends \yii\db\ActiveRecord
{
    use MyIsamTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'trim'],
            [['content'], 'string', 'max' => 50],
//            [['content'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return TagQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TagQuery(get_called_class());
    }

    /**
     * @throws \yii\db\Exception
     */
    public function afterDelete()
    {
        $this->onDeleteCascade('tag2article', ['tag_id' => $this->id]);
        parent::afterDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['id' => 'article_id'])
            ->viaTable('tag2article', ['tag_id' => 'id']);
    }

    /**
     * @param array $aTag [tag1, tag2, ...]
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public static function insertMany(array $aTag): array
    {
        $aParams = [];
        foreach ($aTag as $i => $tag) {
            $aParams[":ph_$i"] = $tag;
        }
        $sPlaceHolders = '(' . implode('),(', array_keys($aParams)) . ')';
        $sql = "INSERT INTO tag (content) VALUES $sPlaceHolders on DUPLICATE key UPDATE  content=content;";
        Tag::getDb()->createCommand($sql, $aParams)->execute();

        return Tag::find()
            ->select('id')
            ->where(['content' => $aTag])
            ->column();
    }
}
