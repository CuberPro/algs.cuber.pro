<?php

namespace app\models\user;

use Yii;

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
        $valid = $this->validatePassword($this->plainPassword, $user->password);
        if (!$valid) {
            return false;
        }
        return Yii::$app->user->login($user, $this->remember ? Yii::$app->params['user.rememberLoginTime'] : 0);
    }

    private function validatePassword($plainPassword, $savedHash) {

        // empty password
        if ($savedHash === self::EMPTY_PASSWORD) {
            $this->addError(
                'plainPassword',
                "This user doesn't have a password set, please use associated 3rd party website to login."
            );
            return false;
        }

        $valid = Yii::$app->security->validatePassword($plainPassword, $savedHash);
        if (!$valid) {
            $this->addError('plainPassword', 'The password is incorrect.');
            return false;
        }
        return true;
    }

}
