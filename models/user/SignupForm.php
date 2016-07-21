<?php

namespace app\models\user;

use Yii;

class SignupForm extends Users {

    public $plainPassword;
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
            [['email', 'plainPassword', 'name'], 'required'],
            ['email', 'unique'],
            [['email', 'name'], 'trim'],
            ['email', 'email'],
            ['name', 'string', 'length' => [1, 20]],
            ['plainPassword', 'string', 'length' => [8, 32]],
            ['plainPassword', 'match', 'pattern' => '/^(?=.*[A-Za-z])(?=.*[0-9]).{8,32}$/', 'message' => 'The password must have at least one letter and one digit'],
            ['u', 'safe'],
        ];
    }

    public function signup() {
        $valid = $this->validate();
        if (!$valid) {
            return false;
        }
        $this->password = Yii::$app->security->generatePasswordHash($this->plainPassword);
        $this->status = self::STATUS_NEEDS_CONFIRM;
        $success = $this->save();
        if (!$success) {
            return false;
        }
        return Yii::$app->user->login($this);
    }
}
