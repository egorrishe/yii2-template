<?php

namespace common\models\blog;

use Yii;

/**
 * This is the model class for table "tag2article".
 *
 * @property int $tag_id
 * @property int $article_id
 */
class Tag2Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag2article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id', 'article_id'], 'required'],
            [['tag_id', 'article_id'], 'integer'],
            [['tag_id', 'article_id'], 'unique', 'targetAttribute' => ['tag_id', 'article_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => Yii::t('app', 'Tag ID'),
            'article_id' => Yii::t('app', 'Article ID'),
        ];
    }


    /**
     * @param array $aTagId
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public static function insertMany($articleId, array $aTagId): void
    {
        $aParams = [];
        foreach ($aTagId as $i => $id) {
            $aParams[":ph_$i"] = $id;
        }
        $sPlaceHolders = '(:ph_a,' . implode('),(:ph_a,', array_keys($aParams)) . ')';
        $aParams[':ph_a'] = $articleId;

        $sql = "INSERT INTO tag2article (article_id, tag_id) VALUES $sPlaceHolders;";
        self::getDb()->createCommand($sql, $aParams)->execute();
    }
}
