<?php

namespace yii2lab\rest\domain\rest;

use Yii;

class UniAction extends BaseAction {

	public $serviceMethod = 'update';
	
	public function run() {
		$body = Yii::$app->request->getBodyParams();
		$response = $this->runServiceMethod($body);
		return $this->responseToArray($response);
	}
	
	private function responseToArray($response) {
		$response = !empty($response) ? $response : [];
		return $response;
	}

}
