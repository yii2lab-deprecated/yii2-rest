<?php

namespace yii2lab\rest\domain\entities;

use yii2lab\domain\BaseEntity;
use yii2lab\helpers\yii\ArrayHelper;

/**
 * Class RestEntity
 *
 * @package yii2lab\rest\domain\entities
 *
 * @property $id
 * @property $tag
 * @property $module_id
 * @property $request
 * @property $response
 * @property $method
 * @property $endpoint
 * @property $description
 * @property $status
 * @property $stored_at
 * @property $favorited_at
 */
class RestEntity extends BaseEntity {
	
	protected $id;
	protected $tag;
	protected $module_id;
	protected $request;
	protected $response;
	protected $method;
	protected $endpoint;
	protected $description;
	protected $status;
	protected $stored_at;
	protected $favorited_at;
	
	public function getMethod() {
		return $this->getFieldValue('method');
	}
	
	public function getEndpoint() {
		return $this->getFieldValue('endpoint');
	}
	
	public function getDescription() {
		return $this->getFieldValue('description');
	}
	
	private function getFieldValue($name) {
		if(empty($this->{$name}) && !empty($this->request[ $name ])) {
			$this->{$name} = $this->request[ $name ];
		}
		return $this->{$name};
	}
	
	public function getRequest() {
		$request = $this->request;
		if(empty($request['method']) && !empty($this->method)) {
			$request['method'] = $this->method;
		}
		if(empty($request['endpoint']) && !empty($this->endpoint)) {
			$request['endpoint'] = $this->endpoint;
		}
		if(empty($request['description']) && !empty($this->description)) {
			$request['description'] = $this->description;
		}
		return $request;
	}
	
	public function getTag()
	{
		$request = $this->getRequest();
		$requestKeys = [
			'method',
			'endpoint',
			'queryKeys',
			'queryValues',
			'queryActives',
			'bodyKeys',
			'bodyValues',
			'bodyActives',
			'headerKeys',
			'headerValues',
			'headerActives',
			'authorization',
		];
		$data = [
			'method' => $this->getMethod(),
			'endpoint' => $this->getEndpoint(),
			'request' => ArrayHelper::extractByKeys($request, $requestKeys),
		];
		ksort($data);
		ksort($data['request']);
		$serializedData = serialize($data);
		$hash = hash('crc32b', $serializedData);
		return $hash;
	}
}