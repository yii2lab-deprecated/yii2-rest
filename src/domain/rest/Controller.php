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
		if(empty($this->service) && !empty($this->serviceName)) {
			$this->service = $this->serviceName;
		}
		$this->service = ControllerHelper::forgeService($this->service);
		$this->initFormat();
	}
	
	private function initFormat() {
		$format = $this->format();
		$this->serializer = [
			'class' => Serializer::class,
			'format' => $format,
		];
	}

}
