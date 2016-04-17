<?php

use yii\bootstrap\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */

if($model)
    $this->title = 'Welcome, ' . $model->username;
?>

<?php if(Yii::$app->user->identity) : ?>
    <div class="user-view">

        <h1><?= Html::encode($this->title) ?></h1>

        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'username',
                [
                    'attribute' => 'created_at',
                    'value' => date('Y-m-d H:m:s', $model->created_at),
                ],
                [
                    'attribute' => 'modified_at',
                    'value' => isset($model->modified_at) ? date('Y-m-d H:m:s', $model->modified_at) : '-',
                ],
                [
                    'attribute' => 'modified_at',
                    'label' => 'Balance',
                    'value' => $model->balance->balance,
                ],
            ],
        ]) ?>

    </div>

<?php else : ?>
    <div class="main-container">You're not logged in</div>
<?php endif; ?>