<?php

use app\modules\blog\widgets\TagsWidget;
use common\models\blog\Article;
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
                    return TagsWidget::widget(['aTags' => $model->tags]);
                },
                'format' => 'raw',
            ],
            'created_date:datetime',
            'updated_date:datetime',
            'description:ntext',
        ],
    ]) ?>

    <article><?= $model->content ?></article>

</div>
