<?php

namespace app\controllers;

use app\models\Balance;
use Yii;
use app\models\Bill;
use app\models\BillSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\User;
use app\models\Transfer;

/**
 * BillController implements the CRUD actions for Bill model.
 */
class BillController extends Controller
{
    /**
     * @inheritdoc
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
     * Lists all Bill models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bill model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Bill model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bill();
        $model->from = Yii::$app->user->identity->id;
        $autocomplete = [];
        $users = User::find()->all();
        foreach( $users as $user) :
            $autocomplete[] = $user->username;
        endforeach;

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->getDb()->beginTransaction();
            try{
                //transaction here
                $model->to = User::find()->where(['username' => $model->to])->one()->id;
                $model->status = 'pending';
                $model->created_at = date('U');
                $model->save();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
                throw new $e;
            }



            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'autocomplete' => $autocomplete,
            ]);
        }
    }

    /**
     * Updates an existing Bill model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Bill model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->goHome();
    }

    /**
     * Finds the Bill model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bill the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bill::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Shows incoming bills
     * @return string
     */
    public function actionOutgoing()
    {
        $searchModel = new BillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Shows outgoing bills
     * @return string
     */
    public function actionIncoming()
    {
        $searchModel = new BillSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAccept($id)
    {
        $transaction = Yii::$app->getDb()->beginTransaction();
        try{
            $model = Bill::find()->where(['id' => $id])->one();
            // creating transfer operation
            $transfer = new Transfer();
            $transfer->from = $model->from;
            $transfer->to = $model->to;
            $transfer->amount = $model->amount;
            $transfer->created_at = date('U');
            $transfer->save();
            // updating bill status
            $model->status = 'paid';
            $model->modified_at = date('U');
            $model->transfer_id = $transfer->id;
            $model->update();
            // updating sender's balance amount
            $balance_sender = Balance::find()->where(['user_id' => $model->from])->one();
            $balance_sender->balance = $balance_sender->balance - $model->amount;
            $balance_sender->modified_at = date('U');
            $balance_sender->save();
            // updating receiver's amount
            $balance_receiver = Balance::find()->where(['user_id' => $model->to])->one();
            $balance_receiver->balance = $balance_receiver->balance + $model->amount;
            $balance_receiver->modified_at = date('U');
            $balance_receiver->save();

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new $e;
        }

        $this->goHome();
    }

}
