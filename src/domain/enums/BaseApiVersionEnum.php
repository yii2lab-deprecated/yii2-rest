<?php

namespace yii2lab\rest\domain\enums;

use yii2lab\extension\enum\base\BaseEnum;

class BaseApiVersionEnum extends BaseEnum {

    public static function getApiVersionNumberList()
    {
        $dirList = self::values();
	    $result = [];
        foreach($dirList as $path) {
            if (preg_match('#v([0-9]+)#i', $path, $matches)) {
                $result[] = $matches[1];
            }
        }
        return $result;
    }

    public static function getApiSubApps()
    {
        $subApps = self::values();
        $result = [];
        foreach($subApps as $app) {
            $result[] = API . '/' . $app;
        }
        return $result;
    }

}
