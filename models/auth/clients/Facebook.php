<?php

namespace app\models\auth\clients;

use Yii;
use yii\authclient\clients\Facebook as FacebookParent;

class Facebook extends FacebookParent {
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
}