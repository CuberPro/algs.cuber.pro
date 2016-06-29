<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Html;
use yii\widgets\Pjax;

$this->title = Yii::t('app', 'Sign In');
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin();
$form = ActiveForm::begin([
    'id' => 'login-form',
    'action' => Url::toRoute(['user/login']),
    'options' => [
        // 'target' => '_blank',
        'data' => [
            'pjax' => true,
        ],
        'class' => 'col-sm-6 col-md-4',
    ],
]);
?>

<?= $form->field($model, 'email')->input('email') ?>
<?= $form->field($model, 'plainPassword')->passwordInput() ?>
<?= $form->field($model, 'u', ['options' => ['class' => 'hide']])->input('url', ['readonly' => true]) ?>
<?= $form->field($model, 'remember')->checkbox()->label('Remember me?') ?>
<?= Html::submitInput('Sign In', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); Pjax::end(); ?>