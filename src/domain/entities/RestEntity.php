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
 * @property $in_collection
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
	protected $in_collection;
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($value) {
		if(empty($this->id)) {
			$this->id = $value;
		}
	}
	
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
		if(!empty($this->request[ $name ])) {
			$this->{$name} = $this->request[ $name ];
		}
		return $this->{$name};
	}
	
	private function forgeRequest($request, $name) {
		if(empty($request[$name]) && !empty($this->{$name})) {
			$request[$name] = $this->{$name};
		}
		return $request;
	}
	
	public function getRequest() {
		$request = $this->request;
		$request = $this->forgeRequest($request, 'method');
		$request = $this->forgeRequest($request, 'endpoint');
		$request = $this->forgeRequest($request, 'description');
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