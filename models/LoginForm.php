<?php

namespace app\models;

use Yii;
use app\models\Users;

class LoginForm extends Users {

    public $plainPassword;
    public $remember = false;
    public $u = '/';

    public function formName() {
        return '';
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'plainPassword' => Yii::t('app', 'Password'),
        ]);
    }

    public function rules() {
        return [
            [['email', 'plainPassword'], 'required'],
            [['email'], 'trim'],
            ['email', 'email'],
            ['plainPassword', 'string'],
            ['remember', 'boolean'],
            ['u', 'safe'],
            ['email', 'exist', 'message' => 'This email hasn\'t been registered yet.'],
        ];
    }

    public function login() {
        if (!$this->validate()) {
            return false;
        }
        $user = self::findOne(['email' => $this->email]);
        if (!$user) {
            return false;
        }
        $valid = Yii::$app->security->validatePassword($this->plainPassword, $user->password);
        if (!$valid) {
            $this->addError('plainPassword', 'The password is incorrect.');
            return false;
        }
        return Yii::$app->user->login($user, $this->remember ? Yii::$app->params['user.rememberLoginTime'] : 0);
    }

}