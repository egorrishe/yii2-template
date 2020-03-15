<?php
namespace common\tests\_fixtures;

use common\models\blog\Tag2Article;
use yii\test\ActiveFixture;

class Tag2ArticleFixture extends ActiveFixture
{
    public $modelClass = Tag2Article::class;
    public $depends = [TagFixture::class, ArticleFixture::class];
}