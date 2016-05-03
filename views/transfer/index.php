<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransferSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transfers';
?>
<div class="transfer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'from',
                'visible' => Yii::$app->controller->action->id == 'outgoing' ? false : true,
                'value' => function($model){
                    return isset($model->userFrom->username) ? $model->userFrom->username : '';
                }
            ],
            [
                'attribute' => 'to',
                'visible' => Yii::$app->controller->action->id == 'incoming' ? false : true,
                'value' => function($model){
                    return $model->userTo->username;
                }
            ],
            'amount',
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    return date('d-m-Y h:i:s', $model->created_at);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
