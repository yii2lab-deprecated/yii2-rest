<?php

namespace yii2lab\rest\domain\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;
use yii2lab\app\domain\helpers\EnvService;
use yii2lab\extension\yii\helpers\FileHelper;

class MiscHelper {
	
	public static function isValidVersion($value) {
		return in_array($value, static::getAllVersions());
	}
	
	static function getAllVersions(): array {
		$dirList = FileHelper::scanDir(ROOT_DIR . DS . API);
		$versions = [];
		foreach($dirList as $dir) {
			if(preg_match('/^v(\d+)$/', $dir, $matches)) {
				$versions[$matches[0]] = $matches[1];
			}
		}
		return array_flip($versions);
	}
	
	static function forgeApiUrl($uri = null, $apiVersion = API_VERSION) {
		$apiUrl = self::getBaseApiUrl($apiVersion);
		if(!empty($uri)) {
			$apiUrl .= SL . $uri;
		}
		return Url::to($apiUrl);
	}
	
	static function getBaseApiUrl($apiVersion = API_VERSION) {
		$apiVersionString = is_numeric($apiVersion) ? 'v' . $apiVersion : $apiVersion;
		$baseUrl = EnvService::getUrl('api', $apiVersionString);
		return $baseUrl;
	}
	
	static function matchEntityId($response, $exp = '\/(\d+)$') {
		if (preg_match('#' . $exp . '#', $response->headers['location'], $matches)) {
			return $matches[1];
		} else {
			throw new ServerErrorHttpException('Response header location not found!');
		}
	}
	
	static function setHttp201($uri, $apiVersion = API_VERSION_STRING) {
		Yii::$app->response->statusCode = 201;
		$url = EnvService::getUrl('api', $apiVersion . SL . $uri);
		Yii::$app->response->headers->add('Location', $url);
	}
	
	static function currentApiVersion() {
		preg_match('#v(\d)#', Yii::$app->controller->module->id, $matches);
		$apiVersion = ArrayHelper::getValue($matches, '1');
		if(!empty($apiVersion)) {
			return $apiVersion;
		}
		preg_match('#/v(\d)/#', Yii::$app->request->url, $matches);
		$apiVersion = ArrayHelper::getValue($matches, '1');
		if(!empty($apiVersion)) {
			return $apiVersion;
		}
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