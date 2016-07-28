<?php

namespace app\models\auth\clients;

use yii\authclient\clients\Twitter as TwitterParent;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\auth\AuthHelper;

class Twitter extends TwitterParent {
    public $attributeNames = [
        'name',
        'email',
        'id',
    ];

    public function init() {
        parent::init();
        $state = AuthHelper::generateState();
        $this->returnUrl = Url::toRoute([
            'oauth/auth',
            'authclient' => $this->name,
            'state' => $state,
        ], 'https');
    }

    protected function initUserAttributes() {
        $user = $this->api('account/verify_credentials.json', 'GET', ['include_email' => 'true']);
        $user['id'] = ArrayHelper::getValue($user, 'id_str', strval($user['id']));
        return $user;
    }
}
