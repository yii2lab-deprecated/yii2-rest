<?php

namespace yii2lab\rest\domain\helpers\postman;

use yii2lab\helpers\StringHelper;
use yii2lab\misc\enums\HttpMethodEnum;
use yii2lab\rest\domain\entities\RequestEntity;

class GeneratorHelper {
	
	public static function genRequest(RequestEntity $requestEntity) {
		$result['method'] = $requestEntity->method;
		$result['header'] = GeneratorHelper::genHeaders($requestEntity);
		$result['body'] = GeneratorHelper::genPostBody($requestEntity);
		$result['url'] = GeneratorHelper::genUrl($requestEntity);
		$result['description'] = $requestEntity->description;
		return $result;
	}
	
	public static function genEvent($preRequest = null, $test = null) {
		$result = [];
		if($preRequest) {
			$result[] = [
				'listen' => 'prerequest',
				'script' => [
					'id' => StringHelper::genUuid(),
					'type' => 'text/javascript',
					'exec' => explode(PHP_EOL, trim($preRequest)),
				],
			];
		}
		if($test) {
			$result[] = [
				'listen' => 'test',
				'script' => [
					'id' => StringHelper::genUuid(),
					'type' => 'text/javascript',
					'exec' => explode(PHP_EOL, trim($test)),
				]
			];
		}
		return $result;
	}
	
	public static function genHeaders(RequestEntity $requestEntity) {
		$result = [];
		$headers = $requestEntity->headers;
		if($requestEntity->authorization) {
			$headers['Authorization'] = '{{auth_token}}';
		}
		if($headers) {
			foreach($headers as $key => $value) {
				$result[] = [
					'key' => $key,
					'value' => $value,
				];
			}
		}
		return $result;
	}
	
	public static function genPostBody(RequestEntity $requestEntity) {
		$result = null;
		if($requestEntity->method == HttpMethodEnum::POST && $requestEntity->data) {
			$body = [];
			foreach($requestEntity->data as $key => $value) {
				$body[] = [
					'key' => $key,
					'value' => $value,
					'description' => '',
					'type' => 'text',
				];
			}
			$result = [
				'mode' => 'urlencoded',
				'urlencoded' => $body,
			];
		}
		return $result;
	}
	
	public static function genUrl(RequestEntity $requestEntity) {
		$url = [
			'raw' => '{{host}}/' . $requestEntity->uri,
			'host' => [
				'{{host}}',
			],
			'path' => explode('/', $requestEntity->uri),
		];
		if($requestEntity->method == HttpMethodEnum::GET && $requestEntity->data) {
			$query = [];
			foreach($requestEntity->data as $key => $value) {
				$query[] = [
					'key' => $key,
					'value' => $value,
				];
			}
			$url['query'] = $query;
		}
		return $url;
	}
	
}