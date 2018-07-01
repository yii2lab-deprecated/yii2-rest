<?php

namespace yii2lab\rest\web\helpers;

use yii2lab\misc\enums\HttpHeaderEnum;
use yii2lab\rest\domain\entities\RequestEntity;

class RestHelper {
	
	public static function sendRequest(RequestEntity $model) {
		$login = $model->authorization;
		if(empty($login)) {
			$record = Request::send($model);
			return $record;
		}
		$record = self::sendRequestWithToken($model);
		if($record->status == 401) {
			Token::save($login, null);
			$record = self::sendRequestWithToken($model);
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