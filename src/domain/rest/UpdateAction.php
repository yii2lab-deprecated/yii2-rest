<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii2lab\extension\web\enums\ActionEventEnum;

class UpdateAction extends BaseAction {

	public $serviceMethod = 'update';
	public $successStatusCode = 204;
	
	public function run($id) {
		$body = Yii::$app->request->getBodyParams();
		$body = $this->callActionTrigger(ActionEventEnum::BEFORE_WRITE, $body);
		$response = $this->runServiceMethod($id, $body);
		$response = $this->callActionTrigger(ActionEventEnum::AFTER_WRITE, $response);
		return $response;
	}
}
