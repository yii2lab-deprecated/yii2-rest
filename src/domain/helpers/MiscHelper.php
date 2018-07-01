<?php

namespace yii2lab\rest\domain\helpers;

use Yii;
use yii\helpers\Inflector;

class MiscHelper {
	
	static function currentApiVersion() {
		preg_match('#/v(\d)/#', Yii::$app->request->url, $matches);
		$apiVersion = $matches[1];
		if(!empty($apiVersion)) {
			return $apiVersion;
		}
		preg_match('#v(\d)#', Yii::$app->controller->module->id, $matches);
		$apiVersion = $matches[1];
		return $apiVersion;
    }
	
	static function moduleId($version = null) {
		$version = $version ?: self::currentApiVersion();
		return "rest-v{$version}";
	}
 
	static function collectionName($version = null) {
		$version = $version ?: self::currentApiVersion();
		return Yii::$app->name . SPC . 'v' . $version;
	}
	
	static function collectionNameFormatId() {
		$apiVersion = MiscHelper::currentApiVersion();
		$collectionName = MiscHelper::collectionName($apiVersion);
		$collectionName = Inflector::camelize($collectionName);
		$collectionName = Inflector::camel2id($collectionName);
		return $collectionName;
	}
}