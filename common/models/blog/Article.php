<?php

namespace common\models\blog;

use common\models\user\User;
use common\traits\models\MyIsamTrait;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "article".
 * ENGINE=MyISAM (due to performance)
 *
 * @property int         $id
 * @property int         $user_id
 * @property int         $status
 * @property int         $created_date
 * @property int         $updated_date
 * @property string      $title
 * @property string|null $description
 * @property string|null $content
 *
 * @property User        $user
 * @property Tag[]       $tags
 */
class Article extends \yii\db\ActiveRecord
{
    use MyIsamTrait;

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

    public function scenarios()
    {
        $res = parent::scenarios();

        //making 'user_id' unsafe
        $k = array_search('user_id', $res[self::SCENARIO_DEFAULT]);
        if (false !== $k) {
            $res[self::SCENARIO_DEFAULT][$k] = '!user_id';
        }

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'title'], 'required'],
            [['status'], 'integer'],
            [['description', 'content'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['title', 'description', 'content'], 'trim'],

            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
     * @throws \yii\db\Exception
     */
    public function afterDelete()
    {
        $this->onDeleteCascade('tag2article', ['article_id' => $this->id]);
        parent::afterDelete();
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

    public function getTags(): TagQuery
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])
            ->viaTable('tag2article', ['article_id' => 'id']);
    }

    public function isCurrentUserAuthor(): bool
    {
        if (Yii::$app->user->isGuest || Yii::$app->user->id != $this->user_id) {
            return false;
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;
        return $user->isBlogAuthor();
    }
}
