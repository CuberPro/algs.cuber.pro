<?php

namespace app\models\auth\clients;

use Yii;
use yii\authclient\clients\Google as GoogleParent;
use yii\helpers\ArrayHelper;

class Google extends GoogleParent {
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
