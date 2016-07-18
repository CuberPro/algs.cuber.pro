<?php

namespace app\models\auth\clients;

use Yii;
use yii\authclient\clients\GoogleOAuth;
use yii\helpers\ArrayHelper;
use app\utils\Converter;

class Google extends GoogleOAuth {
    use CommonTrait;

    public $attributeNames = [
        'name',
        'email',
        'id',
    ];

    public function init() {
        parent::init();
        $this->setUrls();
    }

    protected function initUserAttributes() {
        return $this->api('https://www.googleapis.com/userinfo/v2/me', 'GET', [
            'fields' => implode(',', $this->attributeNames),
        ]);
    }
}