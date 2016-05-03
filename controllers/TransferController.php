<?php

namespace app\controllers;

use app\models\Balance;
use app\models\User;
use Yii;
use app\models\Transfer;
use app\models\TransferSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransferController implements the CRUD actions for Transfer model.
 */
class TransferController extends Controller
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
     * Lists all Transfer models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TransferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transfer model.
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
     * Creates a new Transfer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transfer();
        $model->from = Yii::$app->request->get('user_id');
        $autocomplete = [];
        $users = User::find()->all();
        foreach( $users as $user) :
            $autocomplete[] = $user->username;
        endforeach;

        if ($model->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->getDb()->beginTransaction();
            try{
                $model->created_at = time('U');
                // if transition is for the non-existing user
                if (!User::find()->where(['username' => $model->to])->exists()) :
                    (new User([
                        'username' => $model->to,
                        'created_at' => date('U'),
                    ]))->save();
                endif;
                $model->to = User::find()->where(['username' => $model->to])->one()->id;
                $model->save();
                // decreasing sender's balance
                $balance_sender = Balance::find()->where(['user_id' => $model->from])->one();
                $balance_sender->balance = $balance_sender->balance - $model->amount;
                $balance_sender->modified_at = date('U');
                $balance_sender->save();
                // increasing receiver's balance
                if(!Balance::find()->where(['user_id' => $model->to])->exists()) :
                    (new Balance([
                        'user_id' => $model->to,
                        'balance' => $model->amount,
                        'modified_at' => date('U'),
                    ]))->save();
                else :
                    $balance_receiver = Balance::find()->where(['user_id' => $model->to])->one();
                    $balance_receiver->balance = $balance_receiver->balance + $model->amount;
                    $balance_receiver->modified_at = date('U');
                    $balance_receiver->save();
                endif;
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
     * Updates an existing Transfer model.
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
     * Deletes an existing Transfer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Transfer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transfer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transfer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Shows all incoming payments
     * @return string
     */
    public function actionIncoming()
    {
        $searchModel = new TransferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Shows all outgoing payments
     * @return string
     */
    public function actionOutgoing()
    {
        $searchModel = new TransferSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
