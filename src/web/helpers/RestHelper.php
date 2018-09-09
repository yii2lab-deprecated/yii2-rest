<?php

namespace yii2lab\rest\web\helpers;

use yii2lab\extension\web\enums\HttpHeaderEnum;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\web\models\RequestForm;

class RestHelper {
	
	public static function sendRequest(RequestForm $model) {
		$requestEntity = AdapterHelper::createRequestEntityFromForm($model);
		$login = $requestEntity->authorization;
		if(empty($login)) {
			$record = Request::send($requestEntity);
			return $record;
		}
		$record = self::sendRequestWithToken($requestEntity);
		if($record->status == 401) {
			Token::save($login, null);
			$record = self::sendRequestWithToken($requestEntity);
		}
		return $record;
	}
	
	private static function sendRequestWithToken(RequestEntity $model) {
		$token = self::getTokenByLogin($model->authorization);
		$modelAuth = self::putTokenInModel($model, $token);
		$record = Request::send($modelAuth);
		return $record;
	}
	
	private static function getTokenByLogin($login) {
		$token = $storedToken = Token::load($login);
		if(empty($token)) {
			$token = Authorization::getTokenByLogin($login);
		}
		if($token != $storedToken) {
			Token::save($login, $token);
		}
		return $token;
	}
	
	private static function putTokenInModel(RequestEntity $model, $token) {
		$modelAuth = clone $model;
		if(!empty($token)) {
			$headers = $modelAuth->headers;
			$headers[HttpHeaderEnum::AUTHORIZATION] = $token;
			$modelAuth->headers = $headers;
		}
		return $modelAuth;
	}
	
}