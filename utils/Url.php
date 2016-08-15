<?php

namespace app\utils;

class Url {

    public static function base64UrlEncode($str) {
        if (!is_string($str)) {
            return false;
        }
        return strtr(base64_encode($str), '+/=', '-._');
    }

    public static function base64UrlDecode($str) {
        if (!is_string($str)) {
            return false;
        }
        $result = strtr($str, '-._', '+/=');
        return base64_decode($result);
    }

    public static function buildUrl($baseUrl, $params = []) {
        $url = $baseUrl;

        $fragment = '';
        $pos = strpos($url, '#');
        if ($pos !== false) {
            $fragment = substr($url, $pos);
            $url = substr($url, 0, $pos);
        }
        $query = [];
        $pos = strpos($url, '?');
        if ($pos !== false) {
            $queryStr = substr($url, $pos + 1);
            $url = substr($url, 0, $pos);
            parse_str($queryStr, $query);
        }
        $query = array_merge($query, $params);
        if (!empty($query)) {
            $url .= '?' . http_build_query($query, null, '&', PHP_QUERY_RFC3986);
        }
        if (!empty($fragment)) {
            $url .= $fragment;
        }
        return $url;
    }
}
