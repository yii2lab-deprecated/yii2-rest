<?php

namespace yii2lab\rest\domain;

use yii2lab\domain\enums\Driver;

class Domain extends \yii2lab\domain\Domain {
	
	public function config() {
		return [
			'repositories' => [
				'client' => Driver::REST,
			],
			'services' => [
				'client',
			],
		];
	}
	
}