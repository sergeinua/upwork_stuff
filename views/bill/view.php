<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bill */

$this->title = $model->id;
?>
<div class="bill-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    <?php if(Yii::$app->user->identity->id == $model->from) : ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
    <?php else : ?>
        <?= Html::a('Accept', ['accept', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Decline', ['decline', 'id' => $model->id], ['class' => 'btn btn-danger']) ?>
    <?php endif; ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'from',
                'value' => $model->userFrom->username,
            ],
            [
                'attribute' => 'to',
                'value' => $model->userTo->username,
            ],
            'amount',
            'status',
            [
                'attribute' => 'created_at',
                'value' => date('d-m-Y h:i:s', $model->created_at),
            ],
            [
                'attribute' => 'transfer_id',
                'value' => isset($model->transfer_id) ? $model->transfer_id : 'pending',
            ],
        ],
    ]) ?>

</div>
