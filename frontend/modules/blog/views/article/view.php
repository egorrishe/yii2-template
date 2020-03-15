<?php

use common\models\blog\Article;
use common\models\blog\ArticleSearch;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model Article */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="article-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($model->isCurrentUserAuthor()) { ?>
        <p>
            <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php } ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'tagList',
                'value'     => function (Article $model, $widget) {
                    $res = '';
                    foreach ($model->tags as $tag) {
                        $url = ArticleSearch::url($tag);
                        $res .= Html::a($tag->content, $url) . ' ';
                    }

                    return $res;
                },
                'format' => 'raw',
            ],
            'created_date:date',
            'updated_date:date',
            'title',
            'description:ntext',
            'content:ntext',
        ],
    ]) ?>

</div>
