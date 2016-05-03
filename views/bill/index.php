<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BillSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bills';
?>
<div class="bill-index">

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
                    return $model->userFrom->username;
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
            'status',
            // 'created_at',
            // 'transfer_id',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
