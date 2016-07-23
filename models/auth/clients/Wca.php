<?php

namespace app\models\auth\clients;

use yii\authclient\OAuth2;

class Wca extends OAuth2 {
    use CommonTrait;

    public $authUrl = 'https://www.worldcubeassociation.org/oauth/authorize';

    public $tokenUrl = 'https://www.worldcubeassociation.org/oauth/token';

    public $apiBaseUrl = 'https://www.worldcubeassociation.org/api/v0';

    public function init() {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(' ', [
                'public',
                'email',
            ]);
        }
        $this->setUrls();
    }

    protected function initUserAttributes() {
        $user = $this->api('me');
        return isset($user['me']) ? $user['me'] : [];
    }

    protected function defaultName() {
        return 'wca';
    }

    protected function defaultTitle() {
        return 'WCA';
    }
}
