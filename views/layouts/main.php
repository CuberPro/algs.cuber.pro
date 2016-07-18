<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

if (Yii::$app->user->identity != null) {
    $userItem = [
        [
            'label' => Yii::$app->user->identity->name,
            'items' => [
                [
                    'label' => 'Sign Out',
                    'url' => Url::toRoute(['user/logout', 'u' => Url::to()]),
                ],
            ],
        ],
    ];
} else {
    $userItem = [
        [
            'label' => 'Sign In',
            'url' => Url::toRoute(['user/login', 'u' => Url::to()]),
        ],
        [
            'label' => 'Sign Up',
            'url' => Url::toRoute(['user/signup', 'u' => Url::to()]),
        ],
    ];
}
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
        'brandLabel' => 'Cubing Algs',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => array_merge([], $userItem),
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <span class="pull-left copyright">&copy; Cuber.Pro <?= date('Y') ?></span>

    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
