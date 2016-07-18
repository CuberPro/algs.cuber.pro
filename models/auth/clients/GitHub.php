<?php

namespace app\models\auth\clients;

use Yii;
use yii\authclient\clients\GitHub as GitHubParent;

class GitHub extends GitHubParent {
    use CommonTrait;

    public function init() {
        parent::init();
        $this->setUrls();
    }
}
