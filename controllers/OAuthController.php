<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\db\Exception as DbException;
use app\models\user\Users;
use app\models\user\Auth;
use app\utils\Converter;

class OAuthController extends Controller {

    public function actions() {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function beforeAction($action) {
        $state = Yii::$app->request->get('state', '');
        $state = json_decode(Converter::base64UrlDecode($state), true);
        $loginParams = ['u' => ArrayHelper::getValue($state, 'u', '/')];
        $this->action->cancelUrl = Url::toRoute(array_merge(Yii::$app->user->loginUrl, $loginParams));
        return true;
    }

    public function onAuthSuccess($client) {
        $state = Yii::$app->request->get('state', '');
        $state = json_decode(Converter::base64UrlDecode($state), true);
        $this->action->successUrl = ArrayHelper::getValue($state, 'u', '/');

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

        // match user by email
        $user = Users::findOne(['email' => $email]);
        // user found
        if ($user) {
            $auth->user_id = $user->id;
            $res = $auth->save();
            if (!$res) {
                throw new DbException('Save auth data failed');
            }
            // we consider all users from these sites have a validated email
            if ($user->status !== Users::STATUS_ACTIVATED) {
                if (isset($wcaid)) {
                    $user->wcaid = $wcaid;
                }
                $user->status = Users::STATUS_ACTIVATED;
                $res = $user->save();
                if (!$res) {
                    throw new DbException('Update user status failed');
                }
            } elseif (isset($wcaid) && $user->wcaid !== $wcaid) {
                $user->wcaid = $wcaid;
                $user->save();
            }
            Yii::$app->user->login($user);
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

        Users::getDb()->transaction(function($db) use ($user, $auth) {
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
