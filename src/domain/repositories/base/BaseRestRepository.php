<?php

namespace yii2lab\rest\domain\repositories\base;

use yii\helpers\ArrayHelper;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\misc\enums\HttpMethodEnum;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\helpers\RestHelper;

class BaseRestRepository extends BaseRepository {

    public $baseUrl = '';
	public $headers = [];
	public $options = [];
	
	protected function get($uri, array $data = [], array $headers = [], array $options = []) {
		$requestEntity = new RequestEntity;
		$requestEntity->method = HttpMethodEnum::GET;
		$requestEntity->uri = $uri;
		$requestEntity->data = $data;
		$requestEntity->headers = $headers;
		$requestEntity->options = $options;
		return $this->sendRequest($requestEntity);
	}
	
	protected function post($uri, array $data = [], array $headers = [], array $options = []) {
		$requestEntity = new RequestEntity;
		$requestEntity->method = HttpMethodEnum::POST;
		$requestEntity->uri = $uri;
		$requestEntity->data = $data;
		$requestEntity->headers = $headers;
		$requestEntity->options = $options;
		return $this->sendRequest($requestEntity);
	}
	
	protected function put($uri, array $data = [], array $headers = [], array $options = []) {
		$requestEntity = new RequestEntity;
		$requestEntity->method = HttpMethodEnum::PUT;
		$requestEntity->uri = $uri;
		$requestEntity->data = $data;
		$requestEntity->headers = $headers;
		$requestEntity->options = $options;
		return $this->sendRequest($requestEntity);
	}
	
	protected function delete($uri, array $data = [], array $headers = [], array $options = []) {
		$requestEntity = new RequestEntity;
		$requestEntity->method = HttpMethodEnum::DELETE;
		$requestEntity->uri = $uri;
		$requestEntity->data = $data;
		$requestEntity->headers = $headers;
		$requestEntity->options = $options;
		return $this->sendRequest($requestEntity);
	}
	
	protected function sendRequest(RequestEntity $requestEntity) {
		$requestEntity = $this->normalizeRequestEntity($requestEntity);
		return RestHelper::sendRequest($requestEntity);
	}
	
	private function normalizeRequestEntity(RequestEntity $requestEntity) {
		$resultUrl = rtrim($this->baseUrl, SL);
		$uri = trim($requestEntity->uri, SL);
		if(!empty($uri)) {
			$resultUrl .= SL . $uri;
		}
		$requestEntity->uri = $resultUrl;
		if(!empty($this->headers)) {
			$requestEntity->headers = ArrayHelper::merge($requestEntity->headers, $this->headers);
		}
		if(!empty($this->options)) {
			$requestEntity->options = ArrayHelper::merge($requestEntity->options, $this->options);
		}
		return $requestEntity;
	}
}
