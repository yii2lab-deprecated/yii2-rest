<?php

namespace yii2lab\rest\domain\rest;

use Yii;
use yii\base\Action;
use yii2lab\extension\web\helpers\ControllerHelper;

/**
 * Class BaseAction
 *
 * @package yii2lab\rest\domain\rest
 *
 * @property \yii2lab\rest\domain\rest\Controller $controller
 */
class BaseAction extends Action {

	public $service;
	public $serviceMethod;
	public $serviceMethodParams = [];
	public $successStatusCode = 200;

	public function behaviors() {
		return $this->controller->behaviors();
	}
	
	public function init() {
		parent::init();
		$this->service = ControllerHelper::forgeService($this->getService());
	}
	
	protected function callActionTrigger($eventName, $data = null) {
		$event = ControllerHelper::runActionTrigger($this, $eventName, $data);
		return $event->result;
	}
	
	protected function runServiceMethod() {
		$args = func_get_args();
		$response = ControllerHelper::runServiceMethod($this->service, $this->serviceMethod, $args, $this->serviceMethodParams);
		
		Yii::$app->response->setStatusCode($this->successStatusCode);
		if($this->successStatusCode != 200) {
			$response = null;
		}
		return $response;
	}
	
	private function getService() {
		if(!empty($this->service)) {
			return $this->service;
		}
		return $this->service = $this->controller->service;
	}

}
