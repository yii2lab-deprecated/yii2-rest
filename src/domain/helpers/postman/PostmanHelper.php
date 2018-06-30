<?php

namespace yii2lab\rest\domain\helpers\postman;

use yii\helpers\Json;
use yii\web\ServerErrorHttpException;
use yii2lab\helpers\StringHelper;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\helpers\MiscHelper;
use yii2lab\rest\domain\helpers\RouteHelper;

class PostmanHelper {
	
	public static function generateJson($apiVersion, $postmanVersion = '2.1') {
		$collection = PostmanHelper::generate($apiVersion, $postmanVersion);
		$code = Json::encode($collection, JSON_PRETTY_PRINT);
		return $code;
	}
	
	public static function generate($apiVersion, $postmanVersion = '2.1') {
		$all = RouteHelper::allFromRestClient($apiVersion);
		if($postmanVersion == '2.1') {
			return PostmanHelper::genFromCollection($all, $apiVersion);
		}
		throw new ServerErrorHttpException("Postman version $postmanVersion not specified!");
	}
	
	private static function genFromCollection($groupCollection, $apiVersion) {
		$items = AuthorizationHelper::genAuthCollection();
		foreach($groupCollection as $groupName => $group) {
			/** @var requestEntity $requestEntity */
			foreach($group as $name => $requestEntity) {
				$request = GeneratorHelper::genRequest($requestEntity);
				$items[] = [
					'name' => $requestEntity->uri . ($request['description'] ? " ({$request['description']})" : ''),
					'event' => GeneratorHelper::genEvent(),
					'request' => $request,
					'response' => [],
				];
			}
		}
		$groupCollection = [
			'info' => [
				'_postman_id' => StringHelper::genUuid(),
				'name' => MiscHelper::collectionName($apiVersion),
				'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
			],
			'item' => $items,
		];
		return $groupCollection;
	}
	
}