<?php
namespace app\modules\blog\widgets;

use common\models\blog\ArticleSearch;
use common\models\blog\Tag;
use yii\base\Widget;
use yii\helpers\Html;

class TagsWidget extends Widget
{
    /** @var Tag[] */
    public $aTags;

    public function run()
    {
        $res = '';

        foreach ($this->aTags as $tag) {
            $url = ArticleSearch::url($tag);
            $res .= Html::a(Html::encode($tag->content), $url) . ' ';
        }

        return $res;
    }
}