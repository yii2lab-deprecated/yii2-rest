<?php

namespace yii2lab\rest\domain\helpers;

use Yii;
use yii\helpers\Inflector;
use yii\web\ServerErrorHttpException;

class MiscHelper {
	
	static function matchEntityId($response, $exp = '\/(\d+)$') {
		if (preg_match('#' . $exp . '#', $response->headers['location'], $matches)) {
			return $matches[1];
		} else {
			throw new ServerErrorHttpException('Response header location not found!');
		}
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