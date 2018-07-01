<?php

namespace yii2lab\rest\web\helpers;

use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\web\models\RequestForm;

class AdapterHelper {
	
	public static function createRequestEntityFromForm(RequestForm $model) {
		$requestData = self::prepareRequest($model);
		$requestEntity = new RequestEntity($requestData);
		return $requestEntity;
	}
	
	private static function prepareRequest(RequestForm $model) {
		$query = self::prepareRequestData($model->queryKeys, $model->queryValues, $model->queryActives);
		$request = [
			'method' => $model->method,
			'uri' => self::getUri($model->endpoint, $query),
			'description' => $model->description,
			'authorization' => $model->authorization,
			'data' => self::prepareRequestData($model->bodyKeys, $model->bodyValues, $model->bodyActives),
			'headers' => self::prepareRequestData($model->headerKeys, $model->headerValues, $model->headerActives),
		];
		return $request;
	}
	
	private static function prepareRequestData($keys, $values, $actives) {
		$result = [];
		foreach($keys as $index => $key) {
			if($actives[ $index ]) {
				$result[ $key ] = $values[ $index ];
			}
		}
		return $result;
	}
	
	private static function getUri($uri, $data) {
		$query = self::buildQuery($data);
		if(!empty($query)) {
			return $uri . '?' . $query;
		}
		return $uri;
	}
	
	private static function buildQuery($data) {
		$couples = [];
		foreach($data as $key => $value) {
			$couples[] = self::encodeQueryKey($key) . '=' . urlencode($value);
		}
		$query = join('&', $couples);
		$query = trim($query);
		return $query;
	}
	
	private static function encodeQueryKey($key) {
		$encodedKey = urlencode($key);
		return str_replace(['%5B', '%5D'], ['[', ']'], $encodedKey);
	}
}