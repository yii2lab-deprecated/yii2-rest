<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii2lab\extension\web\enums\ActionEventEnum;

class CreateAction extends BaseAction {

	public $serviceMethod = 'create';
	public $successStatusCode = 201;
	
	public function run() {
		$body = Yii::$app->request->getBodyParams();
		$body = $this->callActionTrigger(ActionEventEnum::BEFORE_WRITE, $body);
		$response = $this->runServiceMethod($body);
		$response = $this->callActionTrigger(ActionEventEnum::AFTER_WRITE, $response);
		return $response;
	}
}
