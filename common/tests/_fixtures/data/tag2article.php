<?php

use common\models\blog\Article;
use common\models\blog\Tag;

$aTag = Tag::find()
    ->select('content, id')
    ->createCommand()
    ->queryAll(PDO::FETCH_KEY_PAIR);

$aArticle = Article::find()
    ->select('title, id')
    ->createCommand()
    ->queryAll(PDO::FETCH_KEY_PAIR);

//print_r($aArticle);
//die;

return [
    [
        //use it only in TagCest::checkOnDeleteCascade() - else it may be deleted
        'tag_id'     => $aTag['checkOnDeleteCascade'],
        'article_id' => $aArticle['Test 1'],
    ],
];
