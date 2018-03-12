<?php

namespace yii2lab\rest\web\helpers;

use yii2lab\misc\enums\HttpHeaderEnum;
use yii2lab\rest\web\models\RequestForm;

class RestHelper
{
	
	public static function sendRequest(RequestForm $model)
	{
		$login = $model->authorization;
		if(empty($model->authorization)) {
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
	
	private static function sendRequestWithToken($model) {
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
	
	private static function putTokenInModel(RequestForm $model, $token)
	{
		$modelAuth = clone $model;
		if(!empty($token)) {
			$modelAuth->headerKeys[] = HttpHeaderEnum::AUTHORIZATION;
			$modelAuth->headerValues[] = $token;
			$modelAuth->headerActives[] = 1;
		}
		return $modelAuth;
	}

}