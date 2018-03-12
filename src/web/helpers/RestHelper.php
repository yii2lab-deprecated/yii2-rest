<?php

namespace yii2lab\rest\web\helpers;

use yii2lab\misc\enums\HttpHeaderEnum;

class RestHelper
{
	
	public static function sendRequest($model)
	{
		if(empty($model->authorization)) {
			$record = Request::send($model);
			return $record;
		}
		$token = self::getTokenByLogin($model->authorization);
		$record = self::putTokenInModel($model, $token);
		if($record->status == 401) {
			$token = Authorization::getTokenByLogin($model->authorization);
			$record = self::putTokenInModel($model, $token);
		}
		
		return $record;
	}
	
	private static function getTokenByLogin($login) {
		$token = Token::load($login);
		if(empty($token)) {
			$token = Authorization::getTokenByLogin($login);
		}
		if(!empty($token)) {
			Token::save($login, $token);
		}
		return $token;
	}
	
	private static function putTokenInModel($model, $token)
	{
		$modelAuth = clone $model;
		if(!empty($token)) {
			$modelAuth->headerKeys[] = HttpHeaderEnum::AUTHORIZATION;
			$modelAuth->headerValues[] = $token;
			$modelAuth->headerActives[] = 1;
		}
		$record = Request::send($modelAuth);
		return $record;
	}

}