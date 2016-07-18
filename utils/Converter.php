<?php

namespace app\utils;

class Converter {

    public static function base64UrlEncode($str) {
        if (!is_string($str)) {
            return false;
        }
        return strtr(base64_encode($str), '+/=', '-*_');
    }

    public static function base64UrlDecode($str) {
        if (!is_string($str)) {
            return false;
        }
        $result = strtr($str, '-*_', '+/=');
        return base64_decode($result);
    }
}
