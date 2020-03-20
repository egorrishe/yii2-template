<?php

namespace app\modules\blog\controllers;

use common\models\user\User;
use frontend\modules\blog\models\ArticleForm;
use Yii;
use common\models\blog\Article;
use common\models\blog\ArticleSearch;
use yii\db\ActiveQuery;
use yii\db\ExpressionInterface;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => $this->fnValidateAuthorAccess(),
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ArticleForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $q = ArticleForm::find()->where(['user_id' => Yii::$app->user->id]);
        $model = $this->findModel($id, $q);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        /** @noinspection MissedFieldInspection */
        $this->findModel($id, ['user_id' => Yii::$app->user->id])
            ->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param string|array|ExpressionInterface|ActiveQuery $condition
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $condition = null)
    {
        if ($condition instanceof ActiveQuery) {
            $q = $condition;
            $condition = $q->where;
            $q->where(['id' => $id]);
            if (!empty($condition)) {
                $q->andWhere($condition);
            }
        } else {
            $q = Article::find()->where(['id' => $id]);
            if (!empty($condition)) {
                $q->andWhere($condition);
            }
        }

        $model = $q->one();

        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    private function fnValidateAuthorAccess()
    {
        return static function () {
            /** @var User $user */
            $user = Yii::$app->user->identity;

            if (!$user->isBlogAuthor()) {
                throw new ForbiddenHttpException(Yii::t('app', 'Only authorized users can manage articles. Please contact us for farther instructions.'));
            }

            return true;
        };
    }
}
