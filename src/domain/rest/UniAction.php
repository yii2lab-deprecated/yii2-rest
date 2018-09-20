<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii2lab\extension\web\enums\ActionEventEnum;

class UniAction extends BaseAction {

	public $serviceMethod = 'update';
	
	public function run() {
		$body = Yii::$app->request->getBodyParams();
		//$body = $this->callActionTrigger(ActionEventEnum::BEFORE_WRITE, $body);
		$response = $this->runServiceMethod($body);
		return $this->responseToArray($response);
	}
	
	protected function responseToArray($response) {
		$response = !empty($response) ? $response : [];
		return $response;
	}

}
