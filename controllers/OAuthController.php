<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\db\Exception as DbException;
use app\models\user\Users;
use app\models\user\Auth;
use app\models\auth\AuthHelper;

class OAuthController extends Controller {

    private $state;

    public function actions() {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function init() {
        parent::init();
        $state = Yii::$app->request->get('state', '');
        $this->state = AuthHelper::parseState($state);
    }

    public function beforeAction($action) {
        $loginParams = ['u' => ArrayHelper::getValue($this->state, 'u', '/')];
        $this->action->cancelUrl = Url::toRoute(array_merge(Yii::$app->user->loginUrl, $loginParams));
        return true;
    }

    public function onAuthSuccess($client) {
        $this->action->successUrl = Url::toRoute(ArrayHelper::getValue($this->state, 'u', '/'), 'http');

        $attributes = $client->userAttributes;
        $email = ArrayHelper::getValue($attributes, 'email', null);
        $wcaid = ArrayHelper::getValue($attributes, 'wca_id', null);
        $name = ArrayHelper::getValue($attributes, 'name', null);
        $sourceId = ArrayHelper::getValue($attributes, 'id', null);

        $source = $client->name;

        // find user by auth data
        $auth = Auth::findOne([
            'source' => $source,
            'source_id' => $sourceId,
        ]);
        // auth data found
        if ($auth) {
            $user = $auth->user;
            // user exists
            if ($user) {
                if (isset($wcaid) && $user->wcaid !== $wcaid) {
                    $user->wcaid = $wcaid;
                    $user->save();
                }
                Yii::$app->user->login($user);
                return;
            }
            $auth->delete();
        }

        // no auth data found
        $auth = new Auth;
        $auth->source = $source;
        $auth->source_id = strval($sourceId);
        $auth->source_name = $name;

        // try to match user by email if current user has not logon
        if (Yii::$app->user->isGuest) {
            $user = Users::findOne(['email' => $email]);
        } else { // otherwise choose the current user
            $user = Yii::$app->user->identity;
        }
        // user found
        if ($user) {
            $auth->user_id = $user->id;
            $res = $auth->save();
            if (!$res) {
                throw new DbException('Save auth data failed');
            }
            // we consider all users from these sites have a validated email
            if ($user->status !== Users::STATUS_ACTIVATED && $user->email === $email) {
                $user->status = Users::STATUS_ACTIVATED;
            }
            if (isset($wcaid) && $user->wcaid !== $wcaid) {
                $user->wcaid = $wcaid;
                $user->save();
            }
            $res = $user->save();
            if (!$res) {
                throw new DbException('Update user status failed');
            }
            if (Yii::$app->user->isGuest) {
                Yii::$app->user->login($user);
            }
            return;
        }

        // no user found by any means
        $user = new Users;
        $user->email = $email;
        $user->name = $name;
        // use an illegal password to identify users without passwords
        $user->password = Users::EMPTY_PASSWORD;
        $user->wcaid = $wcaid;
        $user->status = Users::STATUS_ACTIVATED;

        Users::getDb()->transaction(function ($db) use ($user, $auth) {
            $res = $user->save();
            if (!$res) {
                throw new DbException('Create user failed');
            }
            $auth->user_id = $user->id;
            $res = $auth->save();
            if (!$res) {
                throw new DbException('Create auth data failed');
            }
            return true;
        });

        Yii::$app->user->login($user);
        return;
    }
}
