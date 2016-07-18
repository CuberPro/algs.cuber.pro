<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\widgets\Pjax;
use yii\authclient\widgets\AuthChoice;

use app\assets\UserAsset;
use app\assets\OAuthAsset;

$this->title = Yii::t('app', 'Sign Up');
$this->params['breadcrumbs'][] = $this->title;

UserAsset::register($this);
OAuthAsset::register($this);

?>

<div class="user-page">
    <div class="col-sm-6 col-md-4 form">
        <?php Pjax::begin();
        $form = ActiveForm::begin([
    'id' => 'register-form',
    'action' => Url::toRoute(['user/register']),
    'options' => [
        // 'target' => '_blank',
      'data' => [
        'pjax' => true,
      ],
    ],
        ]);
        ?>

        <?= $form->field($model, 'email')->input('email') ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'plainPassword')->passwordInput() ?>
        <?= $form->field($model, 'u', ['options' => ['class' => 'hide']])->input('url', ['readonly' => true]) ?>
        <?= Html::submitInput('Register', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); Pjax::end(); ?>
    </div>
    <div class="col-sm-6 oauth">
        <h4>Sign in with: </h4>
        <?= AuthChoice::widget([
            'baseAuthUrl' => ['oauth/auth'],
            'popupMode' => true,
        ]) ?>
    </div>
</div>
