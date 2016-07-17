<?php

namespace app\models;

use Yii;
use app\models\Users;

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
            ['name', 'unique'],
            [['email', 'name'], 'trim'],
            ['email', 'email'],
            ['name', 'string', 'length' => [1, 20]],
            ['name', 'match', 'pattern' => '/^[a-zA-Z0-9 _-]{1,20}$/', 'message' => 'The name can only consist of letters, digits, spaces, underscores and dashes'],
            ['plainPassword', 'string', 'length' => [8, 32]],
            ['plainPassword', 'match', 'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,32}$/', 'message' => 'The password must have at least one capital letter, one lowercase letter and one digit'],
            ['u', 'safe'],
        ];
    }

    public function register() {
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
