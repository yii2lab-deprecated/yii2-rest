<?php

namespace yii2lab\rest\domain\rest;

use yii\rest\Controller as YiiController;
use yii2lab\domain\services\base\BaseService;
use yii2lab\extension\web\helpers\ControllerHelper;

/**
 * Class Controller
 *
 * @package yii2lab\rest\domain\rest
 *
 * @property null|string|BaseService
 */
class Controller extends YiiController {
	
	public $service = null;
	
	public function format() {
		return [];
	}

	public function init() {
		parent::init();
		$this->initService();
		$this->initFormat();
		$this->initBehaviors();
	}
	
	private function initBehaviors() {
		$controllerBehaviors = param('controllerBehaviors');
		if($controllerBehaviors) {
			$this->attachBehaviors($controllerBehaviors);
		}
	}
	
	private function initService() {
		if(empty($this->service) && !empty($this->serviceName)) {
			$this->service = $this->serviceName;
		}
		$this->service = ControllerHelper::forgeService($this->service);
	}
	
	private function initFormat() {
		$format = $this->format();
		$this->serializer = [
			'class' => Serializer::class,
			'format' => $format,
		];
	}

}
