<?php

namespace yii2lab\rest\domain;

use yii2lab\domain\enums\Driver;
use yii2lab\rest\domain\services\RestService;

/**
 * Class Domain
 *
 * @package yii2lab\rest\domain
 *
 * @property RestService $rest
 */
class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
				'rest' => Driver::ACTIVE_RECORD,
				'client' => Driver::REST,
			],
			'services' => [
				'rest',
				'client',
			],
		];
	}
	
}