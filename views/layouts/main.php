<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\widgets\Menu;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => isset(Yii::$app->getUser()->identity->username) ? Yii::$app->getUser()->identity->username : '',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Home', 'url' => ['/site/index']],
                Yii::$app->user->isGuest ?
                    ['label' => 'Login', 'url' => ['/site/login']] :
                    [
                        'label' => 'Logout (' . Yii::$app->getUser()->identity->username . ')',
                        'url' => ['/site/logout'],
                        'linkOptions' => ['data-method' => 'post']
                    ],
            ],
        ]);
        NavBar::end(); ?>

        <div class="container">
            <div class="row">
                <div class="col-xs-3 col-md-3 col-lg-3 admin-panel">

                    <?php  if(Yii::$app->user->identity) : ?>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">Menu</h3>
                            </div>
                            <?= Menu::widget([
                                'options'=> ['class'=>'sidebar-list sidebar-e'],
                                'items' => [
                                    ['label' => 'Send amount','url' => ['/transfer/create',
                                        'user_id'=>Yii::$app->user->identity->id],
                                        'options' =>['class' => 'sidebar-list-item']],
                                    ['label' => 'Recieved payments', 'url' => ['/transfer/incoming'], 'options' =>['class' => 'sidebar-list-item']],
                                    ['label' => 'Sent payments', 'url' => ['/transfer/outgoing'], 'options' =>['class' => 'sidebar-list-item']],
                                    ['label' => 'Create bill', 'url' => ['/bill/create'], 'options' =>['class' => 'sidebar-list-item']],
                                    ['label' => 'Incoming bills', 'url' => ['/bill/incoming'], 'options' =>['class' => 'sidebar-list-item']],
                                    ['label' => 'Outgoing bills', 'url' => ['/bill/outgoing'], 'options' =>['class' => 'sidebar-list-item']],
                            ]]); ?>
                        </div>
                    <?php endif; ?>

                </div>

                <div class="col-xs-9 col-md-9 col-lg-9 <?= Yii::$app->getUser()->isGuest ? '' : 'logged-in' ?>">
                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>