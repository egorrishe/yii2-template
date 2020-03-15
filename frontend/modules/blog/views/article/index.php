<?php

use kop\y2sp\ScrollPager;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\blog\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Articles');
$this->params['breadcrumbs'][] = $this->title;

$dataProvider->setPagination(['pageSize'=>1]);
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest) { ?>
        <p>
            <?= Html::a(Yii::t('app', 'Create Article'), ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    <?php } ?>

    <?php Pjax::begin(); ?>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions'  => ['class' => 'item'],
        'layout'       => "{items}\n{pager}",
        'itemView'     => function ($model, $key, $index, $widget) {
            $res = '';
            $res .= '<h2>' . Html::a(Html::encode($model->title), ['view', 'id' => $model->id]) . '</h2>';
            $res .= "<article>$model->content</article>";
            $res .= '<hr>';
            $res .= '<hr>';
            $res .= '<hr>';

            return $res;
        },
        'pager' => [
            'class' => ScrollPager::className(),
            'enabledExtensions' => [
                ScrollPager::EXTENSION_SPINNER,
                ScrollPager::EXTENSION_NONE_LEFT,
                ScrollPager::EXTENSION_PAGING,
            ],
        ]
    ]) ?>

    <?php Pjax::end(); ?>

</div>
