<?php

namespace yii2lab\rest\domain\entities;

use yii\helpers\Json;
use yii2lab\domain\BaseEntity;
use yii2lab\extension\web\enums\HttpMethodEnum;
use yii2lab\rest\domain\enums\ContentTriggerHeadersEnum;
use yii2mod\helpers\ArrayHelper;

/**
 * Class RequestEntity
 * @package yii2lab\domain\entities
 *
 * @property $method string
 * @property $uri string
 * @property $data array
 * @property $headers array
 * @property $options array
 * @property $content array
 * @property $cookies array
 * @property $format string
 * @property-read $post array
 * @property-read $query array
 * @property $description string
 * @property $authorization string
 */
class RequestEntity extends BaseEntity
{

	protected $method = HttpMethodEnum::GET;
	protected $uri;
	protected $data = [];
	protected $headers = [];
	protected $options = [];
	protected $cookies = [];
	protected $content = null;
	protected $format = null;
	protected $description = null;
	protected $authorization = null;

	public function rules()
	{
		return [
			[['uri'], 'required'],
			[['method'], 'in', 'range' => HttpMethodEnum::values()],
		];
	}

	public function getMethod()
	{
		return strtoupper($this->method);
	}

	public function getPost()
	{
		return $this->data;
	}

	public function getQuery()
	{
		return $this->data;
	}

	public function setHeaders($value)
	{
		if ($value) {
			$headers = [];
			foreach ($value as $key => $value) {
				$key = mb_strtolower($key);
				if (is_array($value)) {
					$headers[$key] = ArrayHelper::first($value);
				} else {
					$headers[$key] = $value;
				}
				if (!empty(ContentTriggerHeadersEnum::value($key))) {
					$this->content = Json::encode($this->data, 0);
				}
			}

			$this->headers = $headers;
		}
	}
}