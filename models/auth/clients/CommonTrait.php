<?php

namespace app\models\auth\clients;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\utils\Converter;

trait CommonTrait {

    public function buildAuthUrl(array $params = []) {
        $state = Yii::$app->request->queryParams;
        $state = Converter::base64UrlEncode(json_encode($state));
        $params['state'] = ArrayHelper::getValue($params, 'state', $state);

        return parent::buildAuthUrl($params);
    }

    private function setUrls() {
        $this->returnUrl = Url::toRoute(['oauth/auth', 'authclient' => $this->name], 'https');
    }
}
