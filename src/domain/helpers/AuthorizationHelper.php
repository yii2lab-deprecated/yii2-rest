<?php

namespace yii2lab\rest\domain\helpers;

use yii2lab\extension\yii\helpers\ArrayHelper;
use yii2lab\extension\web\enums\HttpHeaderEnum;
use yii2lab\rest\domain\entities\ResponseEntity;

class AuthorizationHelper {
	
	/**
	 * @param $url
	 * @param $login
	 * @param $password
	 *
	 * @return string|null
	 */
	static function getToken($url, $login, $password) {
	    $responseEntity = RestHelper::post($url, [
		    'login' => $login,
		    'password' => $password,
	    ]);
	    if(!$responseEntity->is_ok) {
		    return null;
	    }
	    $token = self::getTokenFromResponse($responseEntity);
	    return $token;
    }
	
	/**
	 * @param ResponseEntity $responseEntity
	 *
	 * @return string|null
	 */
	private static function getTokenFromResponse(ResponseEntity $responseEntity) {
		$token = self::extractTokenFromBody($responseEntity);
		if(empty($token)) {
			$token = self::extractTokenFromHeader($responseEntity);
		}
		return $token;
	}
	
	/**
	 * @param ResponseEntity $responseEntity
	 *
	 * @return string|null
	 */
	private static function extractTokenFromBody(ResponseEntity $responseEntity) {
		return ArrayHelper::getValue($responseEntity->data, 'token', null);
	}
	
	/**
	 * @param ResponseEntity $responseEntity
	 *
	 * @return string|null
	 */
	private static function extractTokenFromHeader(ResponseEntity $responseEntity) {
		return ArrayHelper::getValue($responseEntity->headers, strtolower(HttpHeaderEnum::AUTHORIZATION), null);
	}
}