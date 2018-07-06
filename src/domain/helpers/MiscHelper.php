<?php

namespace yii2lab\rest\domain\helpers;

use Yii;
use yii\helpers\Inflector;
use yii\helpers\Url;

class MiscHelper {
	
	static function forgeApiUrl($uri = null, $apiVersion = API_VERSION) {
		$apiUrl = self::getBaseApiUrl($apiVersion);
		if(!empty($uri)) {
			$apiUrl .= SL . $uri;
		}
		return Url::to($apiUrl);
	}
	
	static function getBaseApiUrl($apiVersion = API_VERSION) {
		$apiVersionString = is_numeric($apiVersion) ? 'v' . $apiVersion : $apiVersion;
		$baseUrl = env('url.api') . $apiVersionString;
		return $baseUrl;
	}
	
	static function setHttp201($uri, $apiVersion = API_VERSION_STRING) {
		Yii::$app->response->statusCode = 201;
		$url = env('url.api') . $apiVersion . SL . $uri;
		Yii::$app->response->headers->add('Location', $url);
	}
	
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