<?php

namespace backend\controllers;

use common\traits\FormAjaxValidationTrait;
use Yii;
use common\models\Writer;
use backend\models\search\WriterSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WriterController implements the CRUD actions for Writer model.
 */
class WriterController extends Controller
{

    use FormAjaxValidationTrait;

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
        ];
    }

    /**
     * Lists all Writer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WriterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        $writer = new Writer();
        $this->performAjaxValidation($writer);


        if ($writer->load(Yii::$app->request->post()) && Yii::$app->request->post('action') == 'create')
        {
            if ( $writer->save()){
                $writer = new Writer();
            }
        }

        if ($writer->load(Yii::$app->request->post()) && Yii::$app->request->post('action') == 'update')
        {
            $model = $this->findModel(Yii::$app->request->post('id'));
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                $writer = new Writer();
            }

        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'writer' => $writer
        ]);
    }

    /**
     * Displays a single Writer model.
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
     * Creates a new Writer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Writer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Writer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Writer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Writer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Writer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Writer::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
