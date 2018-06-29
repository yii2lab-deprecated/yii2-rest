<?php

namespace yii2lab\rest\domain\helpers;

use yii2lab\helpers\StringHelper;
use yii2lab\misc\enums\HttpMethodEnum;
use yii2lab\rest\domain\entities\RequestEntity;

class PostmanHelper
{
	
	public static function genFromCollection($groupCollection) {
		$items = [];
		foreach($groupCollection as $groupName => $group) {
			/** @var requestEntity $requestEntity */
			foreach($group as $name => $requestEntity) {
				$request = self::genRequest($requestEntity);
				$items[] = [
					'name' => $requestEntity->uri . ($request['description'] ? " ({$request['description']})" : ''),
					'event' => self::genEvent(),
					'request' => $request,
					'response' => [],
				];
			}
		}
		
		$groupCollection = [
			'info' => [
				'_postman_id' => StringHelper::genUuid(),
				'name' => 'User',
				'schema' => 'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
			],
			'item' => $items,
		];
		return $groupCollection;
	}
	
    private static function genEvent() {
	    return [
		    [
			    'listen' => 'prerequest',
			    'script' => [
				    'id' => StringHelper::genUuid(),
				    'type' => 'text/javascript',
				    'exec' => [
					    '',
				    ],
			    ],
		    ],
		    [
			    'listen' => 'test',
			    'script' => [
				    'id' => StringHelper::genUuid(),
				    'type' => 'text/javascript',
				    'exec' => [
					    '',
				    ]
			    ]
		    ]
	    ];
    }
	
	private static function genRequest(RequestEntity $requestEntity) {
		//prr($requestEntity,1,1);
		$result['method'] = strtoupper($requestEntity->method);
		
		$headers = $requestEntity->headers;
		
		if($requestEntity->authorization) {
			$headers['Authorization'] = '{{auth_token}}';
		}
		
		if($headers) {
			foreach($headers as $key => $value) {
				$result['header'][] = [
					'key' => $key,
					'value' => $value,
				];
			}
			
		}
		
		if($result['method'] == HttpMethodEnum::POST && $requestEntity->data) {
			$body = [];
			foreach($requestEntity->data as $key => $value) {
				$body[] = [
					'key' => $key,
					'value' => $value,
					'description' => '',
					'type' => 'text',
				];
			}
			$result['body'] = [
				'mode' => 'urlencoded',
				'urlencoded' => $body,
			];
		}
		
		
		
		$result['url'] = [
			'raw' => '{{host}}/' . $requestEntity->uri,
			'host' => [
				'{{host}}',
			],
			'path' => explode('/', $requestEntity->uri),
		];
		
		if($result['method'] == HttpMethodEnum::GET && $requestEntity->data) {
			$query = [];
			foreach($requestEntity->data as $key => $value) {
				$query[] = [
					'key' => $key,
					'value' => $value,
				];
			}
			$result['url']['query'] = $query;
		}
		
		$result['description'] = $requestEntity->description;
		return $result;
	}
    
}