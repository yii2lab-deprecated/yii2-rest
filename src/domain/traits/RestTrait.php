<?php

namespace yii2lab\rest\domain\traits;

use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii2lab\extension\common\helpers\UrlHelper;
use yii2lab\extension\web\enums\HttpMethodEnum;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\entities\ResponseEntity;
use yii2lab\rest\domain\exceptions\UnavailableRestServerHttpException;
use yii2lab\rest\domain\helpers\RestHelper;

trait RestTrait {
	
	public $baseUrl = '';
	public $headers = [];
	public $options = [];
	public $format;
	
	public function get($uri = null, array $data = [], array $headers = [], array $options = []) {
		$requestEntity = new RequestEntity;
		$requestEntity->method = HttpMethodEnum::GET;
		$requestEntity->uri = $uri;
		$requestEntity->data = $data;
		$requestEntity->headers = $headers;
		$requestEntity->options = $options;
		return $this->sendRequest($requestEntity);
	}
	
	public function post($uri = null, array $data = [], array $headers = [], array $options = []) {
		$requestEntity = new RequestEntity;
		$requestEntity->method = HttpMethodEnum::POST;
		$requestEntity->uri = $uri;
		$requestEntity->data = $data;
		$requestEntity->headers = $headers;
		$requestEntity->options = $options;
		return $this->sendRequest($requestEntity);
	}
	
	public function put($uri = null, array $data = [], array $headers = [], array $options = []) {
		$requestEntity = new RequestEntity;
		$requestEntity->method = HttpMethodEnum::PUT;
		$requestEntity->uri = $uri;
		$requestEntity->data = $data;
		$requestEntity->headers = $headers;
		$requestEntity->options = $options;
		return $this->sendRequest($requestEntity);
	}
	
	public function del($uri = null, array $data = [], array $headers = [], array $options = []) {
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
		$responseEntity = RestHelper::sendRequest($requestEntity);
		$this->handleStatusCode($responseEntity);
		return $responseEntity;
	}
	
	protected function handleStatusCode(ResponseEntity $responseEntity) {
		if($responseEntity->is_ok) {
			if($responseEntity->status_code == 201 || $responseEntity->status_code == 204) {
				$responseEntity->content = null;
			}
		} else {
			if($responseEntity->status_code >= 400) {
				$this->showUserException($responseEntity);
			}
			if($responseEntity->status_code >= 500) {
				if($responseEntity->status_code >= 503) {
					throw new UnavailableRestServerHttpException();
				}
				$this->showServerException($responseEntity);
			}
		}
	}
	
	protected function showServerException(ResponseEntity $responseEntity) {
		throw new ServerErrorHttpException();
	}
	
	protected function showUserException(ResponseEntity $responseEntity) {
		$statusCode = $responseEntity->status_code;
		if($statusCode == 401) {
			throw new UnauthorizedHttpException();
		} elseif($statusCode == 403) {
			throw new ForbiddenHttpException();
		} elseif($statusCode == 422) {
			throw new UnprocessableEntityHttpException();
		} elseif($statusCode == 404) {
			throw new NotFoundHttpException(get_called_class());
		}
	}
	
	protected function normalizeRequestEntity(RequestEntity $requestEntity) {
		$this->normalizeRequestEntityUrl($requestEntity);
		if(!empty($this->headers)) {
			$requestEntity->headers = ArrayHelper::merge($requestEntity->headers, $this->headers);
		}
		if(!empty($this->options)) {
			$requestEntity->options = ArrayHelper::merge($requestEntity->options, $this->options);
		}
		if(!empty($this->format)) {
			$requestEntity->format = $this->format;
		}
		return $requestEntity;
	}
	
	private function normalizeRequestEntityUrl(RequestEntity $requestEntity) {
		if(UrlHelper::isAbsolute($requestEntity->uri)) {
			return $requestEntity;
		}
		$resultUrl = rtrim($this->baseUrl, SL);
		$uri = trim($requestEntity->uri, SL);
		if(!empty($uri)) {
			$resultUrl .= SL . $uri;
		}
		$resultUrl = ltrim($resultUrl, SL);
		$requestEntity->uri = $resultUrl;
		return $requestEntity;
	}
	
	public function forgeEntity($data, $class = null) {
		if($data instanceof ResponseEntity) {
			$data = $data->data;
		}
		return parent::forgeEntity($data, $class);
	}
	
}