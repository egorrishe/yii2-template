<?php
namespace common\tests\_fixtures;

use common\models\blog\Article;
use yii\test\ActiveFixture;

class ArticleFixture extends ActiveFixture
{
    public $modelClass = Article::class;
    public $depends = [UserFixture::class];
}