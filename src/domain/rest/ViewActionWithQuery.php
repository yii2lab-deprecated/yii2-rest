<?php

namespace yii2lab\rest\domain\rest;

use yii2lab\extension\web\enums\ActionEventEnum;
use yii2lab\helpers\ClientHelper;

class ViewActionWithQuery extends BaseAction {

	public $serviceMethod = 'oneById';
	
	public function run($id) {
		$this->callActionTrigger(ActionEventEnum::BEFORE_READ);
		$query = ClientHelper::getQueryFromRequest();
		$response = $this->runServiceMethod($id, $query);
		$response = $this->callActionTrigger(ActionEventEnum::AFTER_READ, $response);
		return $response;
	}

}
