<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii2lab\extension\web\enums\ActionEventEnum;

class ViewAction extends BaseAction {

	public $serviceMethod = 'findOne';
	
	public function run($id) {
		$this->callActionTrigger(ActionEventEnum::BEFORE_READ);
		$params = Yii::$app->request->get();
		$response = $this->runServiceMethod($id, $params);
		$response = $this->callActionTrigger(ActionEventEnum::AFTER_READ, $response);
		return $response;
	}
}
