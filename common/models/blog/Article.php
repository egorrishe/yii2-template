<?php

namespace common\models\blog;

use common\models\user\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "article".
 * ENGINE=MyISAM (due to performance)
 *
 * @property int $id
 * @property int $user_id
 * @property int $status
 * @property int $created_date
 * @property int $updated_date
 * @property string $title
 * @property string|null $description
 * @property string|null $content
 *
 * @property User $user
 * @property Tag[] $tags
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_date',
                'updatedAtAttribute' => 'updated_date',
            ],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['status'], 'integer'],
            [['description', 'content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['title', 'description', 'content'], 'trim'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'status' => Yii::t('app', 'Status'),
            'created_date' => Yii::t('app', 'Created Date'),
            'updated_date' => Yii::t('app', 'Updated Date'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ArticleQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticleQuery(get_called_class());
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('tag2article', ['article_id' => 'id']);
    }

    public function isCurrentUserAuthor(): bool
    {
        return (
            !Yii::$app->user->isGuest &&
            (float)Yii::$app->user->id === (float)$this->user_id
        );
    }
}
