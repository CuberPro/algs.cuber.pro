<?php

namespace app\models\auth\clients;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\auth\AuthHelper;

trait CommonTrait {

    public function buildAuthUrl(array $params = []) {
        $state = AuthHelper::generateState();
        $params['state'] = ArrayHelper::getValue($params, 'state', $state);

        $url = parent::buildAuthUrl($params);
        $this->setState('authState', $state); // overwrite state set by framework
        return $url;
    }

    private function setUrls() {
        $this->returnUrl = Url::toRoute(['oauth/auth', 'authclient' => $this->name], 'https');
    }
}
