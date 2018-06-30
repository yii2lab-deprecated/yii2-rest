<?php

namespace yii2lab\rest\domain\helpers;

use Yii;

class MiscHelper {
	
	static function currentApiVersion() {
		preg_match('#/v(\d)/#', Yii::$app->request->url, $matches);
		$apiVersion = $matches[1];
		if(!empty($apiVersion)) {
			return $apiVersion;
		}
		preg_match('#v(\d)#', Yii::$app->controller->module->id, $matches);
		$apiVersion = $matches[1];
		if(!empty($apiVersion)) {
			return $apiVersion;
		}
    }
	
	static function collectionName($version = null) {
		$version = $version ?: self::currentApiVersion();
		return Yii::$app->name . SPC . 'v' . $version;
	}
	
}