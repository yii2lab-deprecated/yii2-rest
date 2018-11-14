<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii2lab\extension\web\enums\ActionEventEnum;
use yii2lab\extension\web\helpers\ClientHelper;

class ViewActionWithQuery extends BaseAction {

	public $serviceMethod = 'oneById';
	
	public function run($id) {
		$this->callActionTrigger(ActionEventEnum::BEFORE_READ);
		$queryParams = Yii::$app->request->get();
		unset($queryParams['id']);
		$query = ClientHelper::getQueryFromRequest($queryParams);
		$response = $this->runServiceMethod($id, $query);
		$response = $this->callActionTrigger(ActionEventEnum::AFTER_READ, $response);
		return $response;
	}

}
