<?php

namespace yii2lab\rest\web\helpers;

use yii2lab\extension\store\Store;

class Token
{

    public static $fileAlias = '@runtime/rest-client/data.json';

    static public function load($login) {
        $store = new Store('Json');
        $data = $store->load(self::$fileAlias, 'token.' . $login);
        return $data;
    }

    static public function save($login, $token) {
        $store = new Store('Json');
        $store->update(self::$fileAlias, 'token.' . $login, $token);
    }

}