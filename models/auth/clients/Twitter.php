<?php

namespace app\models\auth\clients;

use Yii;
use yii\authclient\clients\Twitter as TwitterParent;

class Twitter extends TwitterParent {
    // use CommonTrait;
    public $attributeNames = [
        'name',
        'email',
        'id',
    ];

    public function init() {
        parent::init();
        // $this->setUrls();
    }
}
