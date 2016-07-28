<?php

namespace app\models\auth\clients;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\auth\AuthHelper;

trait CommonTrait {

    public function buildAuthUrl(array $params = []) {
        $state = AuthHelper::generateState();
        $params['state'] = ArrayHelper::getValue($params, 'state', $state);

        return parent::buildAuthUrl($params);
    }

    private function setUrls() {
        $this->returnUrl = Url::toRoute(['oauth/auth', 'authclient' => $this->name], 'https');
    }
}
