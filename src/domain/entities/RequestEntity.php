<?php

namespace yii2lab\rest\domain\entities;

use yii2lab\domain\BaseEntity;
use yii2lab\misc\enums\HttpMethodEnum;

/**
 * Class RequestEntity
 * @package yii2lab\domain\entities
 *
 * @property $method string
 * @property $uri string
 * @property $data array
 * @property $headers array
 * @property $options array
 * @property $cookies array
 * @property $format string
 * @property-read $post array
 * @property-read $query array
 * @property $description string
 * @property $authorization string
 */
class RequestEntity extends BaseEntity {

	protected $method = HttpMethodEnum::GET;
	protected $uri;
	protected $data = [];
	protected $headers = [];
	protected $options = [];
	protected $cookies = [];
	protected $format = null;
	protected $description = null;
	protected $authorization = null;
	
	public function rules() {
		return [
			[['uri'], 'required'],
			[['method'], 'in', 'range' => HttpMethodEnum::values()],
		];
	}
	
	public function getPost() {
		return $this->data;
	}
	
	public function getQuery() {
		return $this->data;
	}
}