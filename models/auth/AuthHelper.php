<?php

namespace app\models\auth;

use Yii;
use app\utils\Converter;

class AuthHelper {

    public static function generateState() {
        $state = Yii::$app->request->queryParams;
        $state = Converter::base64UrlEncode(json_encode($state));
        return $state;
    }

    public static function parseState($encoded) {
        $state = Converter::base64UrlDecode($encoded);
        $state = json_decode($state, true);
        if (!is_array($state)) {
            $state = [];
        }
        return $state;
    }
}
