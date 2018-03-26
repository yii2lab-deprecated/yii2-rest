<?php

namespace yii2lab\rest\domain\repositories\base;

use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii2lab\domain\repositories\BaseRepository;
use yii2lab\misc\enums\HttpMethodEnum;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\entities\ResponseEntity;
use yii2lab\rest\domain\helpers\RestHelper;

abstract class BaseRestRepository extends BaseRepository {

    public $baseUrl = '';
	public $headers = [];
	public $options = [];
	public $format;
	
	protected function get($uri = null, array $data = [], array $headers = [], array $options = []) {
		$requestEntity = new RequestEntity;
		$requestEntity->method = HttpMethodEnum::GET;
		$requestEntity->uri = $uri;
		$requestEntity->data = $data;
		$requestEntity->headers = $headers;
		$requestEntity->options = $options;
		return $this->sendRequest($requestEntity);
	}
	
	protected function post($uri = null, array $data = [], array $headers = [], array $options = []) {
		$requestEntity = new RequestEntity;
		$requestEntity->method = HttpMethodEnum::POST;
		$requestEntity->uri = $uri;
		$requestEntity->data = $data;
		$requestEntity->headers = $headers;
		$requestEntity->options = $options;
		return $this->sendRequest($requestEntity);
	}
	
	protected function put($uri = null, array $data = [], array $headers = [], array $options = []) {
		$requestEntity = new RequestEntity;
		$requestEntity->method = HttpMethodEnum::PUT;
		$requestEntity->uri = $uri;
		$requestEntity->data = $data;
		$requestEntity->headers = $headers;
		$requestEntity->options = $options;
		return $this->sendRequest($requestEntity);
	}
	
	protected function del($uri = null, array $data = [], array $headers = [], array $options = []) {
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
			throw new NotFoundHttpException(static::class);
		}
	}
	
	private function normalizeRequestEntity(RequestEntity $requestEntity) {
		$resultUrl = rtrim($this->baseUrl, SL);
		$uri = trim($requestEntity->uri, SL);
		if(!empty($uri)) {
			$resultUrl .= SL . $uri;
		}
		$resultUrl = ltrim($resultUrl, SL);
		$requestEntity->uri = $resultUrl;
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
}
