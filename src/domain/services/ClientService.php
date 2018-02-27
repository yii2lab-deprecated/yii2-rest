<?php

namespace yii2lab\rest\domain\services;

use yii2lab\domain\services\BaseService;
use yii2lab\misc\enums\HttpMethodEnum;
use yii2lab\rest\domain\entities\RequestEntity;
use yii2lab\rest\domain\repositories\rest\ClientRepository;

/**
 * Class ClientService
 * @package yii2lab\rest\domain\services
 *
 * @property-read ClientRepository $repository
 */
class ClientService extends BaseService {

	public function get($uri, $data = [], $headers = []) {
		return $this->runRequest(compact('uri', 'data', 'headers'), HttpMethodEnum::GET);
	}

	public function post($uri, $data = [], $headers = []) {
		return $this->runRequest(compact('uri', 'data', 'headers'), HttpMethodEnum::POST);
	}

	public function put($uri, $data = [], $headers = []) {
		return $this->runRequest(compact('uri', 'data', 'headers'), HttpMethodEnum::PUT);
	}

	public function delete($uri, $data = [], $headers = []) {
		return $this->runRequest(compact('uri', 'data', 'headers'), HttpMethodEnum::DELETE);
	}

	protected function runRequest($data, $method) {
		$data['method'] = $method;
        $request = new RequestEntity;
        $request->load($data);
        $response = $this->repository->send($request);
		return $response;
	}

}
