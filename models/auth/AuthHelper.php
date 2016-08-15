<?php

namespace app\models\auth;

use Yii;
use app\utils\Url;

class AuthHelper {

    public static function generateState() {
        $state = Yii::$app->request->queryParams;
        $state = Url::base64UrlEncode(json_encode($state));
        return $state;
    }

    public static function parseState($encoded) {
        $state = Url::base64UrlDecode($encoded);
        $state = json_decode($state, true);
        if (!is_array($state)) {
            $state = [];
        }
        return $state;
    }
}
