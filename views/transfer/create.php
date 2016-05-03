<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Transfer */

$this->title = 'Create Transfer';
?>
<div class="transfer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'autocomplete' => $autocomplete,
    ]) ?>

</div>
