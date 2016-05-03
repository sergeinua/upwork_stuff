<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

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

            [
                'attribute' => 'id',
                'value' => function ($model) {
                    return Html::a(Html::encode($model->id), Url::to(['view', 'id' => $model->id]));
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'from',
                'visible' => Yii::$app->controller->action->id == 'outgoing' ? false : true,
                'value' => function($model){
                    return Html::a(Html::encode($model->userFrom->username), Url::to(['view', 'id' => $model->id]));
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'to',
                'visible' => Yii::$app->controller->action->id == 'incoming' ? false : true,
                'value' => function($model){
                    return Html::a(Html::encode($model->userTo->username), Url::to(['view', 'id' => $model->id]));
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'amount',
                'value' => function($model){
                    return Html::a(Html::encode($model->amount), Url::to(['view', 'id' => $model->id]));
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'status',
                'value' => function($model){
                    return Html::a(Html::encode($model->status), Url::to(['view', 'id' => $model->id]));
                },
                'format' => 'raw',
            ],
        ],
    ]); ?>
</div>
