<?php

namespace app\models\auth\clients;

use Yii;
use yii\authclient\clients\Twitter as TwitterParent;
use yii\authclient\OAuthToken;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\utils\Converter;

class Twitter extends TwitterParent {
    public $attributeNames = [
        'name',
        'email',
        'id',
    ];

    public function init() {
        parent::init();
        $state = Yii::$app->request->queryParams;
        $state = Converter::base64UrlEncode(json_encode($state));
        $this->returnUrl = Url::toRoute([
            'oauth/auth',
            'authclient' => $this->name,
            'state' => $state,
        ], 'https');
    }

    protected function initUserAttributes() {
        return $this->api('account/verify_credentials.json', 'GET', ['include_email' => 'true']);
    }
}
